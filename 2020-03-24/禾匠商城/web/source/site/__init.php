<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

if ('entry' != $action) {
	$account_api = WeAccount::createByUniacid();
	if (is_error($account_api)) {
		itoast('', url('account/display'));
	}
	$check_manange = $account_api->checkIntoManage();

	if (is_error($check_manange)) {
		itoast('', $account_api->displayUrl);
	}
	$account_type = $account_api->menuFrame;
	if (!('multi' == $action && 'post' == $do)) {
		define('FRAME', $account_type);
	} else {
		define('FRAME', '');
	}
} else {
	define('FRAME', 'account');
}