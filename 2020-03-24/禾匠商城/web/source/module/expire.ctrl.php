<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('setting');

$dos = array('display', 'update_expire', 'save_expire', 'change_status', 'delete_expire');
$do = in_array($do, $dos) ? $do : 'display';

$system_module_expire = setting_load('system_module_expire');
$system_module_expire = !empty($system_module_expire['system_module_expire']) ? $system_module_expire['system_module_expire'] : '您访问的功能模块不存在，请重新进入';
$module_expire = setting_load('module_expire');
$module_expire = !empty($module_expire['module_expire']) ? $module_expire['module_expire'] : array();
$module_uninstall_total = module_uninstall_total($module_support);
$url = url('module/expire');
if ('display' == $do) {
	template('module/expire');
}

if ('save_expire' == $do) {
	if ($_W['ispost']) {
		if (count($module_expire) >= 5) {
			itoast('最多可设置5条', $url, 'warning');
		}
		$expire['title'] = !empty($_GPC['title']) ? safe_gpc_string($_GPC['title']) : '';
		$expire['notice'] = !empty($_GPC['notice']) ? safe_gpc_string($_GPC['notice']) : '';
		$expire['status'] = 0;
		$module_expire[] = $expire;
		$result = setting_save($module_expire, 'module_expire');
		if (is_error($result)) {
			itoast('添加失败', referer(), 'error');
		}
		itoast('添加成功', $url, 'success');
	}
	template('module/expire_add');
}

if ('update_expire' == $do) {
	$id = safe_gpc_int($_GPC['id']);
	if ($_W['ispost']) {
		$expire['title'] = !empty($_GPC['title']) ? safe_gpc_string($_GPC['title']) : '';
		$expire['notice'] = !empty($_GPC['notice']) ? safe_gpc_string($_GPC['notice']) : '';
		$expire['status'] = $module_expire[$id]['status'];
		$module_expire[$id] = $expire;
		$result = setting_save($module_expire, 'module_expire');
		if (is_error($result)) {
			itoast('设置失败', referer(), 'error');
		}
		itoast('设置成功', $url, 'success');
	}
	$expire = $module_expire[$id];
	if (empty($expire)) {
		itoast('系统错误，请刷新后再试', $url, 'error');
	}
	template('module/expire_add');
}


if ('change_status' == $do) {
	$status = safe_gpc_int($_GPC['status']) ? 1 : 0;
	$id = safe_gpc_int($_GPC['id']);
	foreach ($module_expire as $key => &$value) {
		$value['status'] = 0;
		if($key == $id) {
			$value['status'] = $status;
		}
	}
	$result = setting_save($module_expire, 'module_expire');
	if (is_error($result)) {
		iajax(-1, '设置失败', $url);
	}
	iajax(0, '设置成功', $url);
}

if ('delete_expire' == $do) {
	$id = safe_gpc_int($_GPC['id']);
	unset($module_expire[$id]);
	$result = setting_save($module_expire, 'module_expire');
	if (is_error($result)) {
		iajax(-1, '刪除失败', $url);
	}
	iajax(0, '刪除成功', $url);
}