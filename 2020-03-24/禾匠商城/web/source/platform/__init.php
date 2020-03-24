<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

if (!('material' == $action && 'delete' == $do) && empty($_GPC['version_id'])) {
	$account_api = WeAccount::createByUniacid();
	if (is_error($account_api)) {
		itoast('', url('account/display'));
	}
	$check_manange = $account_api->checkIntoManage();
	if (is_error($check_manange)) {
		itoast('', $account_api->displayUrl);
	}
	if ('detail' == $do) {
		define('FRAME', '');
	} else {
		define('FRAME', 'account');
	}
}

if ('material-post' != $action && FILE_NO_UNIACID != $_GPC['uniacid']) {
	define('FRAME', 'account');
} else {
	define('FRAME', '');
}
if ('qr' == $action) {
	$platform_qr_permission = permission_check_account_user('platform_qr', false);
	if (false === $platform_qr_permission) {
		header('Location: ' . url('platform/url2qr'));
	}
}

if ('url2qr' == $action) {
	define('ACTIVE_FRAME_URL', url('platform/qr'));
}