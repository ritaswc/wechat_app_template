<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
	

error_reporting(0);
define('IN_SYS', true);
define('WECHATS', 1);
define('WXAPP', 4);

function account_tablename($type) {
	$account_types = array(
		WECHATS => 'account_wechats',
		WXAPP => 'account_wxapp',
	);
	return !empty($account_types[$type]) ? $account_types[$type] : '';
}

require '../framework/bootstrap.inc.php';
parse_str($_SERVER['QUERY_STRING'], $query);
if(is_array($query) && count($query) == 3 && in_array($query['type'], array(WECHATS, WXAPP)) && !empty($query['appid']) && !empty($query['secret'])) {
	$table_name = account_tablename($query['type']);
	if (empty($table_name)) {
		exit('Invalid Type');
	}
	$account_info = pdo_get($table_name, array('key' => $query['appid']));
	if (empty($account_info) || empty($account_info['uniacid'])) {
		exit('Appid Not Found');
	}
	$account_api = WeAccount::createByUniacid($account_info['uniacid']);
		$result = array('accesstoken' => $account_api->getAccessToken());
	echo json_encode($result);
	exit;
	
}
exit('Invalid Request');