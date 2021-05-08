<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('miniapp');

if (in_array($action, array('post', 'manage'))) {
	define('FRAME', '');
} else {
	if (!empty($_GPC['uniacid']) && intval($_GPC['uniacid']) != $_W['uniacid']) {
		$params = array('uniacid' => intval($_GPC['uniacid']), 'version_id' => intval($_GPC['version_id']));
		if ('version' == $action && 'display' == $do) {
			$params['miniapp_version_referer'] = 1;
		}

		itoast('', url('account/display/switch', $params));
	}
	$account_api = WeAccount::createByUniacid();
	if (is_error($account_api)) {
		itoast('', url('account/display'));
	}
	$check_manange = $account_api->checkIntoManage();
	if (is_error($check_manange)) {
		itoast('', $account_api->displayUrl);
	}
	$account_type = $account_api->menuFrame;
	if ('version' == $action && 'display' == $do) {
		define('FRAME', '');
	} else {
		define('FRAME', $account_type);
	}
	define('ACCOUNT_TYPE', $account_api->type);
	define('TYPE_SIGN', $account_api->typeSign);
	define('ACCOUNT_TYPE_NAME', $account_api->typeName);
}
$account_all_type = uni_account_type();