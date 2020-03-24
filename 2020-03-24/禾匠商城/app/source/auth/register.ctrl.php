<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$openid = $_W['openid'];
$dos = array('register');
$do = in_array($do, $dos) ? $do : 'register';

$setting = uni_setting($_W['uniacid'], array('passport'));
$item = !empty($setting['passport']['item']) ? $setting['passport']['item'] : 'mobile';
$audit = @intval($setting['passport']['audit']);
$ltype = empty($setting['passport']['type']) ? 'hybird' : $setting['passport']['type'];
$rtype = trim($_GPC['type']) ? trim($_GPC['type']) : 'email';
$forward = url('mc');
if(!empty($_GPC['forward'])) {
	$forward = './index.php?' . base64_decode($_GPC['forward']) . '#wechat_redirect';
}
if(!empty($_W['member']) && (!empty($_W['member']['mobile']) || !empty($_W['member']['email']))) {
	header('location: ' . $forward);
	exit;
}
if($do == 'register') {
	if($_W['ispost'] && $_W['isajax']) {
		$where['uniacid'] = $_W['uniacid'];
		$code = trim($_GPC['code']);
		$username = trim($_GPC['username']);
		$password = trim($_GPC['password']);
		if (empty($code)) {
			$repassword = trim($_GPC['repassword']);
			if ($repassword != $password) {
				message('密码输入不一致', referer(), 'error');
			}
						if($item == 'email') {
				if(preg_match(REGULAR_EMAIL, $username)) {
					$type = 'email';
					$where['email'] = $username;
				} else {
					message('邮箱格式不正确', referer(), 'error');
				}
			} elseif($item == 'mobile') {
				if(preg_match(REGULAR_MOBILE, $username)) {
					$type = 'mobile';
					$where['mobile'] = $username;
				} else {
					message('手机号格式不正确', referer(), 'error');
				}
			} else {
				if (preg_match(REGULAR_MOBILE, $username)) {
					$type = 'mobile';
					$where['mobile'] = $username;
				} elseif (preg_match(REGULAR_EMAIL, $username)) {
					$type = 'email';
					$where['email'] = $username;
				} else {
					message('用户名格式错误', referer(), 'error');
				}
			}
		} else {
			load()->model('utility');
			if (!code_verify($_W['uniacid'], $username, $password)) {
				message('验证码错误', referer(), 'error');
			} else {
				table('uni_verifycode')
					->where(array('receiver' => $username))
					->delete();
			}
			if (preg_match(REGULAR_MOBILE, $username)) {
				$type = 'mobile';
				$where['mobile'] = $username;
			} else {
				message('用户名格式错误', referer(), 'error');
			}
			if ($ltype != 'code' && $audit == '1') {
				$audit_password = trim($_GPC['audit_password']);
				$audit_repassword = trim($_GPC['audit_repassword']);
				if ($audit_password != $audit_repassword) {
					message('密码输入不一致', referer(), 'error');
				}
				$password = $audit_password;
			}
			if ($ltype == 'code' && $audit == '1') {
				$password = '';
			}
		}
		$user = table('mc_members')->where($where)->get();
		if(!empty($user)) {
			message('该用户名已被注册', referer(), 'error');
		}
				if(!empty($_W['openid'])) {
			$fan = mc_fansinfo($_W['openid']);
			if (!empty($fan)) {
				$map_fans = $fan['tag'];
			}
			if (empty($map_fans) && isset($_SESSION['userinfo'])) {
				$map_fans = iunserializer(base64_decode($_SESSION['userinfo']));
			}
		}

		$default_groupid = table('mc_groups')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'isdefault' => 1
			))
			->getcolumn('groupid');
		$data = array(
			'uniacid' => $_W['uniacid'], 
			'salt' => random(8),
			'groupid' => $default_groupid, 
			'createtime' => TIMESTAMP,
		);
		
		$data['email'] = $type == 'email'  ? $username : '';
		$data['mobile'] = $type == 'mobile' ? $username : '';
		if (!empty($password)) {
			$data['password'] = md5($password . $data['salt'] . $_W['config']['setting']['authkey']);
		}
		if (empty($type)) {
			$data['mobile'] = $username;
		}
		if(!empty($map_fans)) {
			$data['nickname'] = strip_emoji($map_fans['nickname']);
			$data['gender'] = $map_fans['sex'];
			$data['residecity'] = $map_fans['city'] ? $map_fans['city'] . '市' : '';
			$data['resideprovince'] = $map_fans['province'] ? $map_fans['province'] . '省' : '';
			$data['nationality'] = $map_fans['country'];
			$data['avatar'] = $map_fans['headimgurl'];
		}

		table('mc_members')->fill($data)->save();
		$user['uid'] = pdo_insertid();
		if (!empty($fan) && !empty($fan['fanid'])) {
			table('mc_mapping_fans')
				->where(array('fanid'=>$fan['fanid']))
				->fill(array('uid'=>$user['uid']))
				->save();
		}
		if(_mc_login($user)) {
			message('注册成功！', referer(), 'success');
		}
		message('未知错误导致注册失败', referer(), 'error');
	}
	template('auth/register');
	exit;
}
