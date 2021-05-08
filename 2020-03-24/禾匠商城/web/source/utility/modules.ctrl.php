<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
error_reporting(0);
load()->model('module');

$dos = array('list');
if (!in_array($do, array('list', 'check_receive'))) {
	exit('Access Denied');
}

if ('check_receive' == $do) {
	$module_name = trim($_GPC['module_name']);
	$module_obj = WeUtility::createModuleReceiver($module_name);
	if (!empty($module_obj)) {
		$module_obj->uniacid = $_W['uniacid'];
		$module_obj->acid = $_W['acid'];
		$module_obj->message = array(
			'event' => 'subscribe',
		);
		if (method_exists($module_obj, 'receive')) {
			$module_obj->receive();

			return iajax(0, '');
		}
	}

	return iajax(1, '');
}

if ('list' == $do) {
	global $_W;
	if (!empty($_COOKIE['special_reply_type'])) {
		$enable_modules = array();
		$_W['account']['modules'] = uni_modules();
		foreach ($_W['account']['modules'] as $m) {
			if (is_array($_W['account']['modules'][$m['name']]['handles']) && in_array($_COOKIE['special_reply_type'], $_W['account']['modules'][$m['name']]['handles'])) {
				$enable_modules[$m['name']] = $m;
				if (file_exists(IA_ROOT . '/addons/' . $m['name'] . '/icon-custom.jpg')) {
					$enable_modules[$m['name']]['icon'] = tomedia(IA_ROOT . '/addons/' . $m['name'] . '/icon-custom.jpg');
				} else {
					$enable_modules[$m['name']]['icon'] = tomedia(IA_ROOT . '/addons/' . $m['name'] . '/icon.jpg');
				}
			}
		}
		setcookie('special_reply_type', '', time() - 3600);
	} else {
		$installedmodulelist = uni_modules();
		foreach ($installedmodulelist as $k => $value) {
			$installedmodulelist[$k]['official'] = empty($value['issystem']) && (strexists($value['author'], 'WeEngine Team') || strexists($value['author'], '微擎团队'));
		}
		foreach ($installedmodulelist as $name => $module) {
			if ($module['issystem']) {
				$path = '/framework/builtin/' . $module['name'];
			} else {
				$path = '../addons/' . $module['name'];
			}
			$cion = $path . '/icon-custom.jpg';
			if (!file_exists($cion)) {
				$cion = $path . '/icon.jpg';
				if (!file_exists($cion)) {
					$cion = './resource/images/nopic-small.jpg';
				}
			}
			$module['icon'] = $cion;
			if (1 == $module['enabled']) {
				$enable_modules[$name] = $module;
			} else {
				$unenable_modules[$name] = $module;
			}
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 21;
	$current_module_list = array_slice($enable_modules, ($pindex - 1) * $psize, $psize);
	$result = array(
		'items' => $current_module_list,
		'pager' => pagination(count($enable_modules), $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback' => 'null')),
	);
	iajax(0, $result);
}
