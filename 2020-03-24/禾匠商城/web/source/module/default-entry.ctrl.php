<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('display');
$do = in_array($do, $dos) ? $do : 'display';

$module_name = trim($_GPC['m']);
$modulelist = uni_modules();
$module = $_W['current_module'] = $modulelist[$module_name];
define('IN_MODULE', $module_name);
if(empty($module)) {
	itoast('抱歉，你操作的模块不能被访问！');
}
if(!permission_check_account_user_module()) {
	itoast('您没有权限进行该操作');
}

if ($do == 'display') {
	$menu_entries = module_entries($module_name, array('menu'));
	$menu_entries = $menu_entries['menu'];
	$default_entry_id = !empty($module['config']) ? intval($module['config']['default_entry']) : 0;

	if (checksubmit()) {
		$default_entry = intval($_GPC['default_entry_id']);
		$data = !empty($module['config']) ? $module['config'] : array();

		if (empty($default_entry)) {
			unset($data['default_entry']);
		} else {
			$data['default_entry'] = $default_entry;
		}

		$insert_data['settings'] = iserializer($data);
		$insert_data['uniacid'] = $_W['uniacid'];
		$insert_data['module'] = $module_name;
		$setting = table('uni_account_modules')->isSettingExists($module_name);
		if (!$setting) {
			$insert_data['enabled'] = 1;
			pdo_insert('uni_account_modules', $insert_data);
		} else {
			pdo_update('uni_account_modules', array('settings' => iserializer($data)), array('uniacid' => $_W['uniacid'], 'module' => $module_name));
		}
		cache_build_module_info($module_name);
		itoast('保存成功！', '', 'success');
	}
	template('module/default-entry');
}