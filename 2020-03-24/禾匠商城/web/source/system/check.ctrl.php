<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('system');

if ('check_table' == $do) {
	$wrong_tables = array();
	$table_pre = $_W['config']['db']['tablepre'] . '_%';
	$tables = pdo_fetchall("SHOW TABLE STATUS LIKE '{$table_pre}'", array(), 'Name');

	foreach ($tables as $table_name => $table_info) {
		if (!empty($table_info['Engine']) && !in_array($table_info['Engine'], array('MyISAM', 'InnoDB'))) {
			unset($tables[$table_name]);
		}
	}

	$tables_str = implode('`,`', array_keys($tables));
	$check_result = pdo_fetchall('CHECK TABLE `' . $tables_str . '`');
	foreach ($check_result as $check_info) {
		if ('OK' != $check_info['Msg_text'] && 'warning' != $check_info['Msg_type']) {
			$wrong_tables[$check_info['Table']] = $check_info;
		}
	}
	$message = array(
		'status' => !empty($wrong_tables) ? -1 : 0,
		'list' => $wrong_tables
	);
	iajax(0, $message);
}

if ('check_fpm' == $do) {
	$result = fastcgi_finish_request();
	if (is_error($result)) {
		$message = array(
			'status' => $result['errno'],
			'message' => $result['message']
		);
		iajax(0, $message);
	}
	exit();
}

if ('check_auth_accounts' == $do) {
	$accounts = pdo_getall('account', array(
		'isconnect' => 1,
		'isdeleted' => 0,
		'type' => array(ACCOUNT_TYPE_OFFCIAL_AUTH, ACCOUNT_TYPE_APP_AUTH, ACCOUNT_TYPE_XZAPP_AUTH),
	));
	$failed_accounts = array();
	if (!empty($accounts)) {
		foreach ($accounts as $account) {
			$uni_account = WeAccount::createByUniacid($account['uniacid']);
			$token = $uni_account->getAccessToken();
			if (is_error($token)) {
				$failed_accounts[] = array(
					'name' => $uni_account->account['name'],
					'acid' => $uni_account->account['acid'],
					'uniacid' => $uni_account->account['uniacid'],
					'type' => $uni_account->account['type'],
					'error' => $token['message'],
					'url' => url('account/post', array('acid' => $uni_account->account['acid'], 'uniacid' => $uni_account->account['uniacid'], 'account_type' => $uni_account->account['type']))
				);
			}
		}
	}
	if (empty($failed_accounts)) {
		$message = array(
			'status' => 0,
			'message' => 'success'
		);
		iajax(0, $message);
	} else {
		$message = array(
			'status' => -1,
			'list' => $failed_accounts
		);
		iajax(0, $message);
	}
}

$system_check_items = system_check_items();
if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
	unset($system_check_items['mcrypt']);
}

foreach ($system_check_items as $check_item_name => &$check_item) {
	$check_item['check_result'] = $check_item['operate']($check_item_name);
}

$check_num = count($system_check_items);
$check_wrong_num = 0;
foreach ($system_check_items as $check_key => $check_val) {
	if (false === $check_val['check_result']) {
		$check_wrong_num += 1;
	}
}

cache_write(cache_system_key('system_check'), array('check_items' => $system_check_items, 'check_num' => $check_num, 'check_wrong_num' => $check_wrong_num));

template('system/check');