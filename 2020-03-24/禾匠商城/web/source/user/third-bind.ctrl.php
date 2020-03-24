<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('user');
$do = safe_gpc_string($_GPC['do']);
$dos = array('display', 'validate_mobile', 'bind_mobile', 'bind_oauth');
$do = in_array($do, $dos) ? $do : 'display';

if (in_array($do, array('validate_mobile', 'bind_mobile'))) {
	$user_profile = table('users_profile')->getByUid($_W['uid']);
	$mobile = safe_gpc_string($_GPC['mobile']);
	$mobile_exists = table('users_bind')->getByTypeAndBindsign(USER_REGISTER_TYPE_MOBILE, $mobile);
	if (empty($mobile)) {
		iajax(-1, '手机号不能为空');
	}
	if (!preg_match(REGULAR_MOBILE, $mobile)) {
		iajax(-1, '手机号格式不正确');
	}
	if (empty($type) && !empty($mobile_exists)) {
		iajax(-1, '手机号已存在');
	}
}

if ('validate_mobile' == $do) {
	iajax(0, '本地校验成功');
}

if ('bind_mobile' == $do) {
	if ($_W['isajax'] && $_W['ispost']) {
		$bind_info = OAuth2Client::create('mobile')->bind();
		if (is_error($bind_info)) {
			iajax(-1, $bind_info['message']);
		}
		iajax(0, '绑定成功', url('user/profile/bind'));
	} else {
		iajax(-1, '非法请求');
	}
}

if ('display' == $do) {
	$support_bind_urls = user_support_urls();
	$setting_sms_sign = setting_load('site_sms_sign');
	$bind_sign = !empty($setting_sms_sign['site_sms_sign']['register']) ? $setting_sms_sign['site_sms_sign']['register'] : '';
	if (!empty($_W['user']['type']) && $_W['user']['type'] == USER_TYPE_CLERK) {
		$_W['setting']['copyright']['bind'] = empty($_W['setting']['copyright']['clerk']['bind']) ? '' : $_W['setting']['copyright']['clerk']['bind'];
	}
}

if ('bind_oauth' == $do) {
	$uid = intval($_GPC['uid']);
	$openid = safe_gpc_string($_GPC['openid']);
	$register_type = intval($_GPC['register_type']);

	if (empty($uid) || empty($openid) || !in_array($register_type, array(USER_REGISTER_TYPE_QQ, USER_REGISTER_TYPE_WECHAT))) {
		itoast('参数错误!', url('user/login'), '');
	}

	$user_info = user_single($uid);
	if ($user_info['is_bind']) {
		itoast('账号已绑定!', url('user/login'), '');
	}

	if ($_W['ispost']) {
		$member['username'] = safe_gpc_string($_GPC['username']);
		$member['password'] = safe_gpc_string($_GPC['password']);
		$member['repassword'] = safe_gpc_string($_GPC['repassword']);
		$member['is_bind'] = 1;

		if (empty($member['username']) || empty($member['password']) || empty($member['repassword'])) {
			itoast('请填写完整信息！', referer(), '');
		}

		if (!preg_match(REGULAR_USERNAME, $member['username'])) {
			itoast('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。', referer(), '');
		}

		if (user_check(array('username' => $member['username']))) {
			itoast('非常抱歉，此用户名已经被注册，你需要更换注册名称！', referer(), '');
		}

		if (istrlen($member['password']) < 8) {
			itoast('必须输入密码，且密码长度不得低于8位。', referer(), '');
		}

		if ($member['password'] != $member['repassword']) {
			itoast('两次秘密输入不一致', referer(), '');
		}
		unset($member['repassword']);

		if (user_check(array('username' => $member['username']))) {
			itoast('非常抱歉，此用户名已经被注册，你需要更换注册名称！', referer(), '');
		}

		$member['salt'] = random(8);
		$member['password'] = user_hash($member['password'], $member['salt']);
		$result = pdo_update('users', $member, array('uid' => $uid, 'openid' => $openid, 'register_type' => $register_type));

		if ($result) {
			itoast('注册绑定成功!', url('user/login'), '');
		} else {
			itoast('注册绑定失败, 请联系管理员解决!', url('user/login'), '');
		}
	} else {
		template('user/bind-oauth');
		exit;
	}
}
template('user/third-bind');