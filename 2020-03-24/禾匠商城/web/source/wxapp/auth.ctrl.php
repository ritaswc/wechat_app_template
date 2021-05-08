<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('communication');
load()->classs('weixin.platform');
load()->classs('wxapp.platform');
load()->model('miniapp');

$dos = array('forward', 'confirm');
$do = in_array($do, $dos) ? $do : 'forward';

$account_platform = new WxappPlatform();

if ($do == 'forward') {

	if (empty($_GPC['auth_code'])) {
		itoast('授权登录失败，请重试', url('account/manage'), 'error');
	}
	$auth_info = $account_platform->getAuthInfo($_GPC['auth_code']);
	if (is_error($auth_info)) {
		itoast('授权登录新建小程序失败：' . $auth_info['message'], url('account/manage'), 'error');
	}
	$auth_refresh_token = $auth_info['authorization_info']['authorizer_refresh_token'];
	$auth_appid = $auth_info['authorization_info']['authorizer_appid'];

	$account_info = $account_platform->getAuthorizerInfo($auth_appid);
	if (is_error($account_info)) {
		itoast('授权登录新建小程序失败：' . $account_info['message'], url('account/manage'), 'error');
	}
	if (!empty($_GPC['test'])) {
		echo "此为测试平台接入返回结果：<br/> 小程序名称：{$account_info['authorizer_info']['nick_name']} <br/> 接入状态：成功";
		exit;
	}
	if ($account_info['authorizer_info']['verify_type_info']['id'] > '-1') {
		$level = 2;
	} else {
		$level = 1;
	}
	$account_found = $account_platform->fetchSameAccountByAppid($auth_appid);
	if (!empty($account_found)) {
		message('小程序已经在系统中接入，是否要更改为授权接入方式？ <div><a class="btn btn-primary" href="' . url('wxapp/auth/confirm', array('level' => $level, 'auth_refresh_token' => $auth_refresh_token, 'auth_appid' => $auth_appid, 'uniacid' => $account_found['uniacid'])) . '">是</a> &nbsp;&nbsp;<a class="btn btn-default" href="index.php">否</a></div>', '', 'tips');
	}

	$account_wxapp_data = array(
		'name' => trim($account_info['authorizer_info']['nick_name']),
		'original' => trim($account_info['authorizer_info']['user_name']),
		'level' => $level,
		'key' => trim($auth_appid),
		'type' => ACCOUNT_TYPE_APP_AUTH,
		'encodingaeskey' => $account_platform->encodingaeskey,
		'auth_refresh_token'=>$auth_refresh_token,
		'token' => $account_platform->token,
		'headimg' => $account_info['authorizer_info']['head_img'],
		'qrcode' => $account_info['authorizer_info']['qrcode_url'],
	);
	$uniacid = miniapp_create($account_wxapp_data);
	if (!$uniacid) {
		itoast('授权登录新建小程序失败，请重试', url('account/manage'), 'error');
	}
	cache_build_account($uniacid);
	itoast('授权登录成功', url('wxapp/post/design_method', array('uniacid' => $uniacid, 'choose_type'=>2)), 'success');
}

if ($do == 'confirm') {
	$auth_refresh_token = safe_gpc_string($_GPC['auth_refresh_token']);
	$auth_appid = safe_gpc_string($_GPC['auth_appid']);
	$level = intval($_GPC['level']);
	$uniacid = intval($_GPC['uniacid']);

	if (user_is_founder($_W['uid'])) {
		$user_accounts = table('account')->getAll();
	} else {
		$user_accounts = uni_user_accounts($_W['uid'], 'wxapp');
	}
	$user_accounts = array_column($user_accounts, 'uniacid');
	if (empty($user_accounts) || !in_array($uniacid, $user_accounts)) {
		itoast('账号或用户信息错误!', url('account/post', array('uniacid' => $uniacid)), 'error');
	}

	table('account_wxapp')
		->where(array('uniacid' => $uniacid))
		->fill(array(
		'auth_refresh_token' => $auth_refresh_token,
		'encodingaeskey' => $account_platform->encodingaeskey,
		'token' => $account_platform->token,
		'level' => $level,
		'key' => $auth_appid,
		))
		->save();
	table('account')->where(array('uniacid' => $uniacid))
		->fill(array(
			'isconnect' => '1',
			'type' => ACCOUNT_TYPE_APP_AUTH,
			'isdeleted' => 0
		))
		->save();

	cache_delete(cache_system_key('uniaccount', array('uniacid' => $uniacid)));
	cache_delete(cache_system_key('accesstoken', array('uniacid' => $uniacid)));
	cache_delete(cache_system_key('account_auth_refreshtoken', array('uniacid' => $uniacid)));
	$url = url('wxapp/post/design_method', array('uniacid' => $uniacid, 'choose_type'=>2));

	itoast('更改小程序授权接入成功', $url, 'success');
}