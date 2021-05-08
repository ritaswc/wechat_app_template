<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->app('common');
load()->app('template');
load()->model('mc');
load()->model('account');
load()->model('attachment');
load()->model('permission');
load()->model('module');
load()->model('visit');

$_W['uniacid'] = intval($_GPC['i']);
if(empty($_W['uniacid'])) {
	$_W['uniacid'] = intval($_GPC['weid']);
}
if(empty($_W['uniacid'])) {
	header('HTTP/1.1 404 Not Found');
	header("status: 404 Not Found");
	exit;
}
$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
if (is_error($_W['account'])) {
	message($_W['account']['message']);
}
if (!empty($_W['account']['isdeleted'])) {
	message('指定公众号已被删除');
}
if (!empty($_W['uniaccount']['endtime']) && TIMESTAMP > $_W['uniaccount']['endtime']  && !in_array($_W['uniaccount']['endtime'], array(USER_ENDTIME_GROUP_EMPTY_TYPE, USER_ENDTIME_GROUP_UNLIMIT_TYPE))) {
	message('抱歉，您的平台账号服务已过期，请及时联系管理员');
}
$_W['acid'] = $_W['uniaccount']['acid'];

	if (visit_app_pass_visit_limit()) {
		message('API（访问流量限制）已用完，请联系管理员进行分配或去商城进行购买！');
	}
	if (!empty($_W['account']['setting']['bind_domain']) && !empty($_W['account']['setting']['bind_domain']['domain']) && strpos($_W['account']['setting']['bind_domain']['domain'], $_SERVER['HTTP_HOST']) === false) {
		header('Location:' . $_W['account']['setting']['bind_domain']['domain']. $_SERVER['REQUEST_URI']);
		exit;
	}

$_W['session_id'] = '';
if (isset($_GPC['state']) && !empty($_GPC['state']) && strexists($_GPC['state'], 'we7sid-')) {
	$pieces = explode('-', $_GPC['state']);
	$_W['session_id'] = $pieces[1];
	unset($pieces);
}
if (empty($_W['session_id'])) {
	$_W['session_id'] = $_COOKIE[session_name()];
}
if (empty($_W['session_id'])) {
	$_W['session_id'] = "{$_W['uniacid']}-" . random(20) ;
	$_W['session_id'] = md5($_W['session_id']);
	setcookie(session_name(), $_W['session_id'], 0, '/');
}
session_id($_W['session_id']);

load()->classs('wesession');
WeSession::start($_W['uniacid'], CLIENT_IP);
if (!empty($_GPC['j'])) {
	$acid = intval($_GPC['j']);
	$_W['account'] = account_fetch($acid);
	if (is_error($_W['account'])) {
		$_W['account'] = account_fetch($_W['acid']);
	} else {
		$_W['acid'] = $acid;
	}
	$_SESSION['__acid'] = $_W['acid'];
	$_SESSION['__uniacid'] = $_W['uniacid'];
}
if (!empty($_SESSION['__acid']) && $_SESSION['__uniacid'] == $_W['uniacid']) {
	$_W['acid'] = intval($_SESSION['__acid']);
	$_W['account'] = uni_fetch($_W['uniacid']);
}
if (strpos($_SERVER['QUERY_STRING'], 'favicon.ico') === false && ((!empty($_SESSION['acid']) && $_W['acid'] != $_SESSION['acid']) ||
		(!empty($_SESSION['uniacid']) && $_W['uniacid'] != $_SESSION['uniacid']))) {
	$keys = array_keys($_SESSION);
	foreach ($keys as $key) {
		unset($_SESSION[$key]);
	}
	unset($keys, $key);
}
$_SESSION['acid'] = $_W['acid'];
$_SESSION['uniacid'] = $_W['uniacid'];

if (!empty($_SESSION['openid'])) {
	$_W['openid'] = $_SESSION['openid'];
	$_W['fans'] = mc_fansinfo($_W['openid']);
	$_W['fans']['from_user'] = $_W['fans']['openid'] = $_W['openid'];
}
if (!empty($_SESSION['uid']) || (!empty($_W['fans']) && !empty($_W['fans']['uid']))) {
	$uid = intval($_SESSION['uid']);
	if (empty($uid)) {
		$uid = $_W['fans']['uid'];
	}
	_mc_login(array('uid' => $uid));
	unset($uid);
}
if (empty($_W['openid']) && !empty($_SESSION['oauth_openid'])) {
	$_W['openid'] = $_SESSION['oauth_openid'];
	$_W['fans'] = array(
		'openid' => $_SESSION['oauth_openid'],
		'from_user' => $_SESSION['oauth_openid'],
		'follow' => 0
	);
}

$_W['oauth_account'] = $_W['account']['oauth'] = array(
	'key' => $_W['account']['key'],
	'secret' => $_W['account']['secret'],
	'acid' => $_W['account']['acid'],
	'type' => $_W['account']['type'],
	'level' => $_W['account']['level'],
	'support_oauthinfo' => $_W['account']->supportOauthInfo,
	'support_jssdk' => $_W['account']->supportJssdk,
);
$unisetting = uni_setting_load();
if (empty($unisetting['oauth']) && $_W['account']->typeSign == 'account' && $_W['account']['level'] != ACCOUNT_SERVICE_VERIFY) {
	$global_oauth = uni_account_global_oauth();
	$unisetting['oauth'] = (array)$global_oauth['oauth'];
}
if (!empty($unisetting['oauth']['account'])) {
	$oauth = account_fetch($unisetting['oauth']['account']);
	if (!empty($oauth) && $_W['account']['level'] <= $oauth['level']) {
		$_W['oauth_account'] = $_W['account']['oauth'] = array(
			'key' => $oauth['key'],
			'secret' => $oauth['secret'],
			'acid' => $oauth['acid'],
			'type' => $oauth['type'],
			'level' => $oauth['level'],
			'support_oauthinfo' => $oauth->supportOauthInfo,
			'support_jssdk' => $oauth->supportJssdk,
		);
		unset($oauth);
	}
}

if($controller != 'utility') {
	$_W['token'] = token();
}

if (!empty($_W['account']['oauth']) && $_W['account']['oauth']['support_oauthinfo'] && empty($_W['isajax']) &&
	(($_W['container'] == 'baidu' && $_W['account']->typeSign != 'account') || $_W['container'] != 'baidu')) {

	if (($_W['platform'] == 'account' && !$_GPC['logout'] && empty($_W['openid']) && ($controller != 'auth' || ($controller == 'auth' && !in_array($action, array('forward', 'oauth'))))) ||
		($_W['platform'] == 'account' && !$_GPC['logout'] && empty($_SESSION['oauth_openid']) && ($controller != 'auth'))) {
		$state = 'we7sid-'.$_W['session_id'];
		if (empty($_SESSION['dest_url'])) {
			$_SESSION['dest_url'] = urlencode($_W['siteurl']);
		}
		$str = '';
		if(uni_is_multi_acid()) {
			$str = "&j={$_W['acid']}";
		}
		$oauth_type = 'snsapi_base';
		if ($controller == 'entry' && !empty($_GPC['m'])) {
			$module_info = module_fetch($_GPC['m']);
			if ($module_info['oauth_type'] == OAUTH_TYPE_USERINFO) {
				$oauth_type = 'snsapi_userinfo';
			}
		}
		$oauth_url = uni_account_oauth_host();
		$url = $oauth_url . "app/index.php?i={$_W['uniacid']}{$str}&c=auth&a=oauth&scope=" . $oauth_type;
		$callback = urlencode($url);
		$oauth_account = WeAccount::create($_W['account']['oauth']);
		if ($oauth_type == 'snsapi_base') {
			$forward = $oauth_account->getOauthCodeUrl($callback, $state);
		} else {
			$forward = $oauth_account->getOauthUserInfoUrl($callback, $state);
		}
		header('Location: ' . $forward);
		exit();
	}

}
$_W['account']['groupid'] = $_W['uniaccount']['groupid'];
$_W['account']['qrcode'] = tomedia('qrcode_'.$_W['acid'].'.jpg').'?time='.$_W['timestamp'];
$_W['account']['avatar'] = tomedia('headimg_'.$_W['acid'].'.jpg').'?time='.$_W['timestamp'];

if ($_W['platform'] == 'account' && $_W['account']->supportJssdk && $controller != 'utility') {
	if (!empty($unisetting['jsauth_acid'])) {
		$jsauth_acid = $unisetting['jsauth_acid'];
	} else {
		if ($_W['account']['level'] < ACCOUNT_SUBSCRIPTION_VERIFY && !empty($unisetting['oauth']['account'])) {
			$jsauth_acid = $unisetting['oauth']['account'];
		} else {
			$jsauth_acid = $_W['acid'];
		}
	}
	if (!empty($jsauth_acid)) {
		$account_api = WeAccount::create($jsauth_acid);
		if (!empty($account_api)) {
			$_W['account']['jssdkconfig'] = $account_api->getJssdkConfig();
			$_W['account']['jsauth_acid'] = $jsauth_acid;
		}
	}
	unset($jsauth_acid, $account_api);
}

$_W['attachurl'] = attachment_set_attach_url();