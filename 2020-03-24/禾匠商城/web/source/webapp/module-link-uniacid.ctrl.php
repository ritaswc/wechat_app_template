<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('module_link_uniacid', 'search_link_account', 'module_unlink_uniacid');
$do = in_array($do, $dos) ? $do : 'module_link_uniacid';

$_W['page']['title'] = '数据同步 - PC - 管理';

if ($do == 'module_link_uniacid') {
	if (checksubmit('submit')) {
		$module_name = trim($_GPC['module_name']);
		$uniacid = intval($_GPC['uniacid']);
		if (empty($module_name) || empty($uniacid)) {
			iajax('1', '参数错误！');
		}
		$module = module_fetch($module_name);
		if (empty($module)) {
			iajax('1', '模块不存在！');
		}

		$account_module = pdo_get('uni_account_modules', array('module' => $module_name, 'uniacid' => $_W['uniacid']), array('id', 'settings'));
		if (!empty($account_module)) {
			$settings = (array)iunserializer($account_module['settings']);
			$settings['link_uniacid'] = $uniacid;
			pdo_update('uni_account_modules', array('settings' => iserializer($settings)), array('id' => $account_module['id'], 'uniacid' => $_W['uniacid']));
		} else {
			$settings = array('link_uniacid' => $uniacid);
			$data = array(
				'settings' => iserializer($settings),
				'uniacid' => $_W['uniacid'],
				'module' => $module_name,
				'enabled' => STATUS_ON,
			);
			pdo_insert('uni_account_modules', $data);
		}
		uni_passive_link_uniacid($uniacid, $module_name);
		cache_build_module_info($module_name);
		iajax(0, '关联成功');
	}

	$modules = uni_modules();
		foreach ($modules as $key => $value) {
		if ($value[MODULE_SUPPORT_WXAPP_NAME] == MODULE_NONSUPPORT_WXAPP && $value[MODULE_SUPPORT_ACCOUNT_NAME] == MODULE_NONSUPPORT_ACCOUNT || !empty($value['issystem'])) {
			unset($modules[$key]);
			continue;
		}
		if (!empty($value['config']) && !empty($value['config']['link_uniacid'])) {
			$modules[$key]['link_uniacid_info'] = uni_fetch($value['config']['link_uniacid']);
			continue;
		}
		if (!empty($value['config']['passive_link_uniacid'])) {
			$modules[$key]['other_link'] = uni_fetch($value['config']['passive_link_uniacid']);
		}
	}
	template('webapp/module-link-uniacid');
}

if ($do == 'module_unlink_uniacid') {
	$module_name = trim($_GPC['module_name']);
	if (empty($module_name)) {
		iajax(-1, '参数错误！');
	}
	$module = module_fetch($module_name);
	if (empty($module)) {
		iajax(-1, '模块不存在！');
	}
	$account_module = pdo_get('uni_account_modules', array('module' => $module_name, 'uniacid' => $_W['uniacid']), array('id', 'settings'));
	if (!empty($account_module)) {
		$settings = iunserializer($account_module['settings']);
		if (empty($settings['link_uniacid'])) {
			$result = true;
		} else {
			unset($settings['link_uniacid']);
			$data = empty($settings) ? '' : iserializer($settings);
			$result = pdo_update('uni_account_modules', array('settings' => $data), array('id' => $account_module['id'], 'uniacid' => $_W['uniacid']));
		}
	}
	if ($result) {
		cache_build_module_info($module_name);
		iajax(0, '删除成功！', referer());
	} else {
		iajax(0, '删除失败！', referer());
	}
}

if ($do == 'search_link_account') {
	$module_name = trim($_GPC['module_name']);
	$account_type = intval($_GPC['type']);
	if (empty($module_name)) {
		iajax(0, array());
	}
	$module = module_fetch($module_name);
	if (empty($module)) {
		iajax(0, array());
	}
	if (!in_array($account_type, array(ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_OFFCIAL_NORMAL))) {
		iajax(0, array());
	}
		$have_link_uniacid = array();
	$link_uniacid_info = module_link_uniacid_info($module_name);
	if (!empty($link_uniacid_info)) {
		foreach ($link_uniacid_info as $info) {
			if (!empty($info['settings']['link_uniacid'])) {
				$have_link_uniacid[] = $info['uniacid'];
			}
		}
	}
		if ($account_type == ACCOUNT_TYPE_OFFCIAL_NORMAL) {
		$account_normal_list = uni_search_link_account($module_name, ACCOUNT_TYPE_OFFCIAL_NORMAL);
		$account_auth_list = uni_search_link_account($module_name, ACCOUNT_TYPE_OFFCIAL_AUTH);
		$account_list = array_merge($account_normal_list, $account_auth_list);
	} else {
		$account_list = uni_search_link_account($module_name, $account_type);
	}
	if (!empty($account_list)) {
		foreach ($account_list as $key => $account) {
			if (in_array($account['uniacid'], $have_link_uniacid)) {
				unset($account_list[$key]);
				continue;
			}
			$account_list[$key]['logo'] = is_file(IA_ROOT . '/attachment/headimg_' . $account['acid'] . '.jpg') ? tomedia('headimg_'.$account['acid']. '.jpg').'?time='.time() : './resource/images/nopic-107.png';
		}
	}
	iajax(0, $account_list);
}