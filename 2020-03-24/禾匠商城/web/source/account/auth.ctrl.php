<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('communication');
load()->classs('weixin.platform');
load()->model('account');
set_time_limit(0);

$dos = array('ticket', 'forward', 'test', 'confirm');
$do = in_array($do, $dos) ? $do : 'forward';

$account_platform = new WeixinPlatform();

if ($do == 'forward') {
	if (empty($_GPC['auth_code'])) {
		itoast('授权登录失败，请重试', url('account/manage'), 'error');
	}
	$auth_info = $account_platform->getAuthInfo($_GPC['auth_code']);
	if (is_error($auth_info)) {
		itoast('授权登录新建公众号失败：' . $auth_info['message'], url('account/manage'), 'error');
	}
	$auth_refresh_token = $auth_info['authorization_info']['authorizer_refresh_token'];
	$auth_appid = $auth_info['authorization_info']['authorizer_appid'];

	$account_info = $account_platform->getAuthorizerInfo($auth_appid);
	if (is_error($account_info)) {
		itoast('授权登录新建公众号失败：' . $account_info['message'], url('account/manage'), 'error');
	}
	if (!empty($_GPC['test'])) {
		echo "此为测试平台接入返回结果：<br/> 公众号名称：{$account_info['authorizer_info']['nick_name']} <br/> 接入状态：成功";
		exit;
	}
	if ($account_info['authorizer_info']['service_type_info']['id'] == '0' || $account_info['authorizer_info']['service_type_info']['id'] == '1') {
		if ($account_info['authorizer_info']['verify_type_info']['id'] > '-1') {
			$level = '3';
		} else {
			$level = '1';
		}
	} elseif ($account_info['authorizer_info']['service_type_info']['id'] == '2') {
		if ($account_info['authorizer_info']['verify_type_info']['id'] > '-1') {
			$level = '4';
		} else {
			$level = '2';
		}
	}
	$account_found = $account_platform->fetchSameAccountByAppid($auth_appid);
	if (!empty($account_found)) {
		message('公众号已经在系统中接入，是否要更改为授权接入方式？ <div><a class="btn btn-primary" href="' . url('account/auth/confirm', array('level' => $level, 'auth_refresh_token' => $auth_refresh_token, 'auth_appid' => $auth_appid, 'uniacid' => $account_found['uniacid'])) . '">是</a> &nbsp;&nbsp;<a class="btn btn-default" href="index.php">否</a></div>', '', 'tips');
	}
	$account_insert = array(
		'name' => $account_info['authorizer_info']['nick_name'],
		'description' => '',
		'groupid' => 0,
	);
	if(!pdo_insert('uni_account', $account_insert)) {
		itoast('授权登录新建公众号失败，请重试', url('account/manage'), 'error');
	}
	$uniacid = pdo_insertid();
	$template = pdo_fetch('SELECT id,title FROM ' . tablename('site_templates') . " WHERE name = 'default'");
	$style_insert = array(
		'uniacid' => $uniacid,
		'templateid' => $template['id'],
		'name' => $template['title'] . '_' . random(4),
	);
	pdo_insert('site_styles', $style_insert);
	$styleid = pdo_insertid();

	$multi_insert = array(
		'uniacid' => $uniacid,
		'title' => $account_insert['name'],
		'styleid' => $styleid,
	);
	pdo_insert('site_multi', $multi_insert);
	$multi_id = pdo_insertid();

	$unisetting_insert = array(
		'creditnames' => iserializer(array(
			'credit1' => array('title' => '积分', 'enabled' => 1),
			'credit2' => array('title' => '余额', 'enabled' => 1)
		)),
		'creditbehaviors' => iserializer(array(
			'activity' => 'credit1',
			'currency' => 'credit2'
		)),
		'uniacid' => $uniacid,
		'default_site' => $multi_id,
		'sync' => iserializer(array('switch' => 0, 'acid' => '')),
	);
	pdo_insert('uni_settings', $unisetting_insert);
	pdo_insert('mc_groups', array('uniacid' => $uniacid, 'title' => '默认会员组', 'isdefault' => 1));

	$account_index_insert = array(
		'uniacid' => $uniacid,
		'type' => ACCOUNT_OAUTH_LOGIN,
		'hash' => random(8),
		'isconnect' => 1
	);
	pdo_insert('account', $account_index_insert);
	$acid = pdo_insertid();

	$subaccount_insert = array(
		'acid' => $acid,
		'uniacid' => $uniacid,
		'name' => $account_insert['name'],
		'account' => $account_info['authorizer_info']['alias'],
		'original' => $account_info['authorizer_info']['user_name'],
		'level' => $level,
		'key' => $auth_appid,
		'auth_refresh_token' => $auth_refresh_token,
		'encodingaeskey' => $account_platform->encodingaeskey,
		'token' => $account_platform->token,
	);
	pdo_insert('account_wechats', $subaccount_insert);
	if(is_error($acid)) {
		itoast('授权登录新建公众号失败，请重试', url('account/manage'), 'error');
	}
	if (user_is_vice_founder()) {
		uni_user_account_role($uniacid, $_W['uid'], ACCOUNT_MANAGE_NAME_VICE_FOUNDER);
	}
	if (empty($_W['isfounder'])) {
		uni_user_account_role($uniacid, $_W['uid'], ACCOUNT_MANAGE_NAME_OWNER);
		if (!empty($_W['user']['owner_uid'])) {
			uni_user_account_role($uniacid, $_W['user']['owner_uid'], ACCOUNT_MANAGE_NAME_VICE_FOUNDER);
		}
	}
	pdo_update('uni_account', array('default_acid' => $acid), array('uniacid' => $uniacid));
	$headimg = ihttp_request($account_info['authorizer_info']['head_img']);
	$qrcode = ihttp_request($account_info['authorizer_info']['qrcode_url']);
	file_put_contents(IA_ROOT . '/attachment/headimg_'.$acid.'.jpg', $headimg['content']);
	file_put_contents(IA_ROOT . '/attachment/qrcode_'.$acid.'.jpg', $qrcode['content']);

	cache_build_account($uniacid);
	cache_delete(cache_system_key('proxy_wechatpay_account'));
	cache_clean(cache_system_key('user_accounts'));
	itoast('授权登录成功', url('account/manage'), 'success');
} elseif ($do == 'confirm') {
	$auth_refresh_token = $_GPC['auth_refresh_token'];
	$auth_appid = $_GPC['auth_appid'];
	$level = intval($_GPC['level']);
	$uniacid = intval($_GPC['uniacid']);

	if (user_is_founder($_W['uid'])) {
		$user_accounts = table('account')->getAll();
	} else {
		$user_accounts = uni_user_accounts($_W['uid']);
	}
	$user_accounts = array_column($user_accounts, 'uniacid');
	if (empty($user_accounts) || !in_array($uniacid, $user_accounts)) {
		itoast('账号或用户信息错误!', url('account/post', array('uniacid' => $uniacid)), 'error');
	}

	pdo_update('account_wechats', array(
		'auth_refresh_token' => $auth_refresh_token,
		'encodingaeskey' => $account_platform->encodingaeskey,
		'token' => $account_platform->token,
		'level' => $level,
		'key' => $auth_appid,
	), array('uniacid' => $uniacid));
	pdo_update('account', array('isconnect' => '1', 'type' => ACCOUNT_OAUTH_LOGIN, 'isdeleted' => 0), array('uniacid' => $uniacid));
	cache_delete(cache_system_key('uniaccount', array('uniacid' => $uniacid)));
	cache_delete(cache_system_key('accesstoken', array('uniacid' => $uniacid)));
	cache_delete(cache_system_key('account_auth_refreshtoken', array('uniacid' => $uniacid)));
	itoast('更改公众号授权接入成功', url('account/post', array('uniacid' => $uniacid)), 'success');
} elseif ($do == 'ticket') {
	$post = file_get_contents('php://input');
	WeUtility::logging('debug', 'account-ticket' . $post);
	$encode_ticket = isimplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);
	if (empty($post) || empty($encode_ticket)) {
		exit('fail');
	}
	$decode_ticket = aes_decode($encode_ticket->Encrypt, $account_platform->encodingaeskey);
	$ticket_xml = isimplexml_load_string($decode_ticket, 'SimpleXMLElement', LIBXML_NOCDATA);
	if (empty($ticket_xml)) {
		exit('fail');
	}
	if (!empty($ticket_xml->ComponentVerifyTicket) && $ticket_xml->InfoType == 'component_verify_ticket') {
		$ticket = strval($ticket_xml->ComponentVerifyTicket);
		setting_save($ticket, 'account_ticket');
	}
	exit('success');
} elseif ($do == 'test') {
	$authurl = $account_platform->getAuthLoginUrl();
	echo '<a href="'.$authurl.'%26test=1"><img src="https://open.weixin.qq.com/zh_CN/htmledition/res/assets/res-design-download/icon_button3_2.png" /></a>';
}