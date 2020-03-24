<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

load()->model('miniapp');
if (strexists($_SERVER['HTTP_REFERER'], 'https://servicewechat.com/')) {
	$referer_url = parse_url($_SERVER['HTTP_REFERER']);
	list($appid, $version) = explode('/', ltrim($referer_url['path'], '/'));
}
if (!empty($_W['uniacid'])) {
	$version_info = miniapp_version_by_version(safe_gpc_string(trim($_GPC['v'])));
	if (!empty($version_info['modules'])) {
		foreach ($version_info['modules'] as $module) {
			if (!empty($module['account']) && intval($module['account']['uniacid']) > 0) {
				$_W['uniacid'] = $module['account']['uniacid'];
				$_W['account']['link_uniacid'] = $module['account']['uniacid'];
			}
		}
	}
}
visit_update_today('app', 'we7_wxapp');
$site = WeUtility::createModuleWxapp($entry['module']);
$method = 'doPage' . ucfirst($entry['do']);
if(!is_error($site)) {
	$site->appid = $appid;
	$site->version = $version;
	if (!empty($site->token)) {
		if (!$site->checkSign()) {
			message(error(1, '签名错误'), '', 'ajax');
		}
	}
	if (!empty($_GPC['state']) && strexists($_GPC['state'], 'we7sid-') && (empty($_W['openid']) || empty($_SESSION['openid']))) {
		$site->result(41009, '请登录');
	}
	exit($site->$method());
}
exit();