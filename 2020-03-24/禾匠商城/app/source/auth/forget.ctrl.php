<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('utility');

$openid = $_W['openid'];
$dos = array('reset', 'forget');
$do = in_array($do, $dos) ? $do : 'forget';

$setting = uni_setting($_W['uniacid'], array('passport'));
$register_mode = is_array($setting['passport']) && !empty($setting['passport']['item']) ? $setting['passport']['item'] : 'random';
$forward = url('mc');
if(!empty($_GPC['forward'])) {
	$forward = './index.php?' . base64_decode($_GPC['forward']) . '#wechat_redirect';
}
if(!empty($_W['member']) && (!empty($_W['member']['mobile']) || !empty($_W['member']['email']))) {
	header('location: ' . $forward);
	exit;
}

if($do == 'reset') {
	if($_W['ispost'] && $_W['isajax']) {
		$code = safe_gpc_string($_GPC['code']);
		$username = safe_gpc_string($_GPC['username']);
		$member_table = table('mc_members');
		switch ($register_mode) {
			case 'mobile':
				$member_table->searchWithMobile($username);
				break;
			case 'email':
				$member_table->searchWithEmail($username);
				break;
			default:
				$member_table->searchWithMobileOrEmail($username);
				break;
		}
		$member_table->searchWithUniacid($_W['uniacid']);
		$member_info = $member_table->get();
		if (empty($member_info)) {
			message('用户不存在', referer(), 'error');
		}

		if(!code_verify($_W['uniacid'], $username, $code)) {
			message('验证码错误', referer(), 'error');
		}

		$password = safe_gpc_string($_GPC['password']);
		$repassword = safe_gpc_string($_GPC['repassword']);
		if ($repassword != $password) {
			message('密码输入不一致', referer(), 'error');
		}

		$password = md5($password . $member_info['salt'] . $_W['config']['setting']['authkey']);
		mc_update($member_info['uid'], array('password' => $password));

		table('uni_verifycode')->where(array('receiver' => $username))->delete();
		message('找回成功', referer(), 'success');
	}
}
template('auth/forget');