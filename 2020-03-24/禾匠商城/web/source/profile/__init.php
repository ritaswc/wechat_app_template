<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

if (strexists($_W['siteurl'], 'c=profile&a=module&do=setting')) {
	$other_params = parse_url($_W['siteurl'], PHP_URL_QUERY);
	$other_params = str_replace('c=profile&a=module&do=setting', '', $other_params);
	itoast('', url('module/manage-account/setting') . $other_params, 'info');
}

$account_api = WeAccount::createByUniacid();
if (is_error($account_api)) {
	itoast('', url('account/display'));
}
$check_manange = $account_api->checkIntoManage();

if (is_error($check_manange) || ($account_api->supportVersion && !in_array($action, array('remote')))) {
	itoast('', $account_api->displayUrl);
}
$account_type = $account_api->menuFrame;
define('FRAME', $account_type);