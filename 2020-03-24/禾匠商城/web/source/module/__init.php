<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

if (in_array($action, array('permission', 'manage-account', 'welcome', 'link-account', 'shortcut', 'plugin'))) {
	$referer = (url_params(referer()));
	if (empty($_GPC['version_id']) && intval($referer['version_id']) > 0 && !$_W['isajax']) {
		itoast('', $_W['siteurl'] . '&version_id=' . $referer['version_id']);
	}
	$account_api = WeAccount::createByUniacid();
	if (is_error($account_api)) {
		itoast('', url('module/display'));
	}
	$check_manange = $account_api->checkIntoManage();
		define('FRAME', 'account');
	if (is_error($check_manange)) {
		itoast('', $account_api->displayUrl);
	}
}
if (in_array($action, array('manage-system', 'expire'))) {
	if (!empty($_GPC['support']) && 'welcome_support' == $_GPC['support']) {
		define('FRAME', 'system');
	} else {
		define('FRAME', 'module_manage');
	}
}
if (in_array($action, array('group'))) {
	define('FRAME', 'permission');
}
if (in_array($action, array('display', 'link'))) {
	define('FRAME', 'module');
}
if (in_array($action, array('link', 'link-account', 'manage-account', 'permission', 'plugin', 'shortcut', 'welcome'))) {
	define('IN_MODULE', trim($_GPC['m']));
}
$account_all_type = uni_account_type();
$account_all_type_sign = array_keys(uni_account_type_sign());
$module_all_support = module_support_type();
$module_support_name = safe_gpc_string($_GPC['support']);
$module_support = !empty($module_all_support[$module_support_name]) ? $module_all_support[$module_support_name]['type'] : 'all';

