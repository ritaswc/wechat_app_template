<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display', 'save');
$do = in_array($_GPC['do'], $dos) ? $do : 'display';
$expired_message_settings = setting_load('account_expired_message');
$expired_message_settings = $expired_message_settings['account_expired_message'];

if ('display' == $do) {
	if (!empty($expired_message_settings)) {
		foreach ($expired_message_settings as $account_type => $account_setting) {
			$account_all_type_sign[$account_type]['expired_message'] = $account_setting;
		}
	}
}

if ('save' == $do) {
	$account_type = safe_gpc_string($_GPC['account_type']);
	$status = intval($_GPC['status']);
	$message = safe_gpc_string($_GPC['message']);

	$expired_message_settings[$account_type] = array('status' => $status, 'message' => $message);

	if ($_W['isajax'] && $_W['ispost']) {
		setting_save($expired_message_settings, 'account_expired_message');
	}

	iajax(0, '保存设置成功', url('account/expired-message'));
}

template('account/manage-expired-message');
