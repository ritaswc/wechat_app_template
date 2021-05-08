<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$openid = $_W['openid'];
$dos = array('basic', 'mobile_exist');
$do = in_array($do, $dos) ? $do : 'basic';

$setting = uni_setting($_W['uniacid'], array('passport'));
$ltype = empty($setting['passport']['type']) ? 'hybird' : $setting['passport']['type'];
$audit = @intval($setting['passport']['audit']);
$item = is_array($setting['passport']) && !empty($setting['passport']['item']) ? $setting['passport']['item'] : 'random';
$type = trim($_GPC['type']) ? trim($_GPC['type']) : 'email';
$forward = url('mc');
if(!empty($_GPC['forward'])) {
	$forward = './index.php?' . base64_decode($_GPC['forward']) . '#wechat_redirect';
}

if($do == 'mobile_exist') {
	if($_W['ispost'] && $_W['isajax']) {
		$type = safe_gpc_string($_GPC['find_mode']);
		$info = safe_gpc_string($_GPC['mobile']);
		$member_table = table('mc_members');
		switch ($type) {
			case 'mobile':
				$member_table->searchWithMobile($info);
				break;
			case 'email':
				$member_table->searchWithEmail($info);
				break;
			default:
				$member_table->searchWithMobileOrEmail($info);
				break;
		}
		$member_table->searchWithUniacid($_W['uniacid']);
		$is_exist = $member_table->get();
		if (!empty($is_exist)) {
			message(error(1, ''), '', 'ajax');
		} else {
			message(error(2, ''), '', 'ajax');
		}
	}
}

if(!empty($_W['member']) && (!empty($_W['member']['mobile']) || !empty($_W['member']['email']))) {
	header('location: ' . $forward);
	exit;
}
if($do == 'basic') {
	if($_W['ispost'] && $_W['isajax']) {
		$username = trim($_GPC['username']);
		$password = trim($_GPC['password']);
		$mode = trim($_GPC['mode']);
		if (empty($username)) {
			message('用户名不能为空', '', 'error');
		}
		if (empty($mode) || !in_array($mode, array('code', 'basic'))) {
			message('非法操作，请刷新页面重试！', '', 'error');
		}
		if (empty($password)) {
			if ($mode == 'code') {
				message('验证码不能为空', '', 'error');
			} else {
				message('密码不能为空', '', 'error');
			}
		}
		if ($mode == 'code') {
			load()->model('utility');
			if (!code_verify($_W['uniacid'], $username, $password)) {
				message('验证码错误', '', 'error');
			} else {
				table('uni_verifycode')->where(array('receiver' => $username))->delete();
			}
		}
		$where['uniacid'] = $_W['uniacid'];
		if ($item == 'mobile') {
			if (preg_match(REGULAR_MOBILE, $username)) {
				$where['mobile'] = $username;
			} else {
				message('请输入正确的手机', '', 'error');
			}
		} elseif ($item == 'email') {
			if (preg_match(REGULAR_EMAIL, $username)) {
				$where['email'] = $username;
			} else {
				message('请输入正确的邮箱', '', 'error');
			}
		} else {
			if (preg_match(REGULAR_MOBILE, $username)) {
				$where['mobile'] = $username;
			} else {
				$where['email'] = $username;
			}
		}
		$user = table('mc_members')
			->select(array('uid', 'salt', 'password'))
			->where($where)
			->get();
		if ($mode == 'basic') {
			$hash = md5($password . $user['salt'] . $_W['config']['setting']['authkey']);
			if ($user['password'] != $hash) {
				message('密码错误', '', 'error');
			}
		}
		if (empty($user)) {
			message('该帐号尚未注册', '', 'error');
		}
		if (_mc_login($user)) {
			message('登录成功！', referer(), 'success');
		}
		message('未知错误导致登录失败', '', 'error');
	}
	template('auth/login');
	exit;
}
