<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('setting');
load()->classs('oauth2/oauth2client');
$dos = array('display', 'save_setting');
$do = in_array($do, $dos) ? $do : 'display';

$types = OAuth2Client::supportLoginType();
$type = safe_gpc_string($_GPC['type']);
$type = !empty($type) && in_array($type, $types) ? $type : 'qq';

$thirdlogin = $_W['setting']['thirdlogin'];
$copyright = $_W['setting']['copyright'];

if ('save_setting' == $do) {
	$is_copyright = false;
	switch ($_GPC['key']) {
		case 'qqauthstate':
			$thirdlogin['qq']['authstate'] = intval($_GPC['value']);
			break;
		case 'qqappid':
			$thirdlogin['qq']['appid'] = safe_gpc_string($_GPC['value']);
			break;
		case 'qqappsecret':
			$thirdlogin['qq']['appsecret'] = safe_gpc_string($_GPC['value']);
			break;
		case 'wechatauthstate':
			$thirdlogin['wechat']['authstate'] = intval($_GPC['value']);
			break;
		case 'wechatappid':
			$thirdlogin['wechat']['appid'] = safe_gpc_string($_GPC['value']);
			break;
		case 'wechatappsecret':
			$thirdlogin['wechat']['appsecret'] = safe_gpc_string($_GPC['value']);
			break;
		case 'bind':
			$copyright['bind'] = safe_gpc_string($_GPC['value']);
			$copyright['bind'] = 'null' == $copyright['bind'] ? '' : $copyright['bind'];
			$is_copyright = true;
			break;
		case 'oauth_bind':
			$copyright['oauth_bind'] = intval($_GPC['value']);
			$is_copyright = true;
			break;
	}
	if ($is_copyright) {
		setting_save($copyright, 'copyright');
	} else {
		setting_save($thirdlogin, 'thirdlogin');
	}
	itoast('更新设置成功！', referer(), 'success');
}

if ('display' == $do) {
	if (empty($thirdlogin)) {
		foreach ($types as $login_type) {
			$thirdlogin[$login_type]['appid'] = '';
			$thirdlogin[$login_type]['appsecret'] = '';
			$thirdlogin[$login_type]['authstate'] = '';
		}
		setting_save($thirdlogin, 'thirdlogin');
	}
	$thirdlogin['bind'] = $copyright['bind'];
	$thirdlogin['oauth_bind'] = $copyright['oauth_bind'];
	$siteroot_parse_array = parse_url($_W['siteroot']);

	$binds = array(array('name' => '无', 'id' => 'null'));
	foreach (OAuth2Client::supportBindTypeInfo() as $info) {
		$binds[] = array('name' => $info['title'], 'id' => $info['type']);
	}
	$bind = array();
	foreach ($binds as $item) {
		if ($item['id'] == $thirdlogin['bind']) {
			$bind = $item;
			break;
		}
	}
	if (empty($bind)) {
		$bind = array('name' => '无', 'id' => 'null');
	}
}
template('system/thirdlogin');