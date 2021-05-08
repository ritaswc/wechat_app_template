<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

function switch_get_account_display() {
	$last_use_account = table('users_lastuse')->getByType('account_display');
	return !empty($last_use_account) ? $last_use_account['uniacid'] : 0;
}

function switch_get_module_display() {
	return table('users_lastuse')->getByType('module_display');
}

function switch_get_user_common_module($module_name) {
	return table('users_lastuse')->getByType('module_common_' . $module_name);
}

function switch_getall_lastuse_by_module($module_name) {
	return table('users_lastuse')->searchWithModulename($module_name)->getall();
}

function switch_save_uniacid($uniacid) {
	global $_W;
	load()->model('visit');
	visit_system_update(array('uniacid' => $uniacid, 'uid' => $_W['uid']));
	isetcookie('__uniacid', $uniacid, 7 * 86400);
	return true;
}

function switch_save_account_display($uniacid) {
	switch_save_uniacid($uniacid);
	return switch_save($uniacid, '', 'account_display');
}

function switch_save_module_display($uniacid, $module_name) {
	global $_W;
	load()->model('visit');
	visit_system_update(array('modulename' => $module_name, 'uid' => $_W['uid'], 'uniacid' => $uniacid));

	return switch_save($uniacid, $module_name, 'module_display');
}

function switch_save_module($uniacid, $module_name) {
	return switch_save($uniacid, $module_name, 'module_display_' . $module_name);
}

function switch_save_user_common_module($uniacid, $module_name) {
	return switch_save($uniacid, $module_name, 'module_common_' . $module_name);
}

function switch_save($uniacid, $module_name, $type) {
	global $_W;
	if (empty($uniacid) || empty($type)) {
		return false;
	}
	$users_lastuse_table = table('users_lastuse');
	$if_exists = $users_lastuse_table->getByType($type);
	$fill_data = array('uniacid' => $uniacid, 'modulename' => $module_name);
	if ($if_exists) {
		$users_lastuse_table->where('id', $if_exists['id'])->fill($fill_data)->save();
	} else {
		$fill_data['uid'] = $_W['uid'];
		$fill_data['type'] = $type;
		$users_lastuse_table->fill($fill_data)->save();
	}
	return true;
}