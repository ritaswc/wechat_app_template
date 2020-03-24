<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('module_link_uniacid', 'search_link_account', 'module_unlink_uniacid');
$do = in_array($do, $dos) ? $do : 'module_link_uniacid';
permission_check_account_user('wxapp_profile_module_link_uniacid');

if ('module_link_uniacid' == $do) {
	if (checksubmit('submit')) {
		$uniacid = intval($_GPC['uniacid']);
		$module_name = safe_gpc_string(trim($_GPC['module_name']));
		if (empty($module_name) || empty($uniacid)) {
			iajax('1', '参数错误！');
		}
		$module = module_fetch($module_name);
		if (empty($module)) {
			iajax('1', '模块不存在！');
		}
		$module_version = array();
		foreach ($version_info['modules'] as $item) {
			if ($item['name'] == $module_name) {
				$module_version = $item;
				break;
			}
		}
		if (empty($module_version)) {
			iajax('1', '模块不存在！');
		}
		$link_uniacid_talbe = table('uni_link_uniacid');
		$sub_uniacids = $link_uniacid_talbe->getSubUniacids($_W['uniacid'], $module_name, $version_id);
		if (!empty($sub_uniacids)) {
			iajax('1', '模块已被其他账号关联！');
		}
		$link_uniacid_talbe->fill(array(
			'uniacid' => $_W['uniacid'],
			'link_uniacid' => $uniacid,
			'version_id' => $version_id,
			'module_name' => $module_name,
		));

		$main_uniacid = $link_uniacid_talbe->getMainUniacid($_W['uniacid'], $module_name, $version_id);
		if (!empty($main_uniacid)) {
			$link_uniacid_talbe->searchWithUniacidModulenameVersionid($_W['uniacid'], $module_name, $version_id);
		}
		$link_uniacid_talbe->save();

		if (!empty($main_uniacid)) {
			cache_clean(cache_system_key('module_setting', array('module_name' => $module_name, 'uniacid' => $main_uniacid)));
		}
		cache_clean(cache_system_key('module_setting', array('module_name' => $module_name, 'uniacid' => $uniacid)));
		cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
		iajax(0, '关联成功');
	}
	if (!empty($version_info['modules'])) {
		foreach ($version_info['modules'] as &$module_value) {
			$passive_link_info = table('uni_link_uniacid')->getSubUniacids($_W['uniacid'], $name, $version_id);
			if (!empty($passive_link_info)) {
				foreach ($passive_link_info as $passive_uniacid) {
					$account = uni_fetch($passive_uniacid);
					$passive_account = $account->account;
					$passive_account['type_name'] = $account->typeName;
					$module_value['passive_link_uniacid'][] = $passive_account;
				}
			}
		}
	}
	template('wxapp/version-module-link-uniacid');
}

if ('module_unlink_uniacid' == $do) {
	if (empty($version_info)) {
		iajax(-1, '版本信息错误！');
	}
	$module_name = safe_gpc_string(trim($_GPC['module_name']));
	if (empty($module_name)) {
		iajax('1', '参数错误！');
	}
	$module = module_fetch($module_name);
	if (empty($module)) {
		iajax('1', '模块不存在！');
	}
	$link_uniacid_table = table('uni_link_uniacid');
	$main_uniacid = $link_uniacid_table->getMainUniacid($_W['uniacid'], $module_name, $version_id);
	if (empty($main_uniacid)) {
		iajax(0, '删除失败！', referer());
	}
	$result = $link_uniacid_table->searchWithUniacidModulenameVersionid($_W['uniacid'], $module_name, $version_id)->delete();
	if ($result) {
		cache_delete(cache_system_key('module_setting', array('module_name' => $module_name, 'uniacid' => $main_uniacid)));
		cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
		cache_build_module_info($module_name);
		iajax(0, '删除成功！', referer());
	} else {
		iajax(0, '删除失败！', referer());
	}
}

if ('search_link_account' == $do) {
	$module_name = safe_gpc_string($_GPC['module_name']);
	$account_type_sign = safe_gpc_string($_GPC['type_sign']);
	if (empty($module_name) || empty($account_type_sign)) {
		iajax(1, '参数不能为空');
	}
	$module = module_fetch($module_name);
	if (empty($module)) {
		iajax(1, '模块不存在或已删除');
	}

	$all_account_type_sign = uni_account_type_sign();
	if (!empty($_W['account']) && WXAPP_TYPE_SIGN != $_W['account']->typeSign) {
		unset($all_account_type_sign[$_W['account']->typeSign]); 	}
	if (!in_array($account_type_sign, array_keys($all_account_type_sign))) {
		iajax(1, '账号类型不存在');
	}

		$link_sub_uniacids = table('uni_link_uniacid')->getAllSubUniacidsByModuleName($module_name);
		$account_list = uni_search_link_account($module_name, $account_type_sign, $_W['uniacid']);
	if (!empty($account_list)) {
		foreach ($account_list as $key => $account) {
			if (in_array($account['uniacid'], $link_sub_uniacids)) {
				unset($account_list[$key]);
				continue;
			}
			$account_list[$key]['logo'] = is_file(IA_ROOT . '/attachment/headimg_' . $account['acid'] . '.jpg') ? tomedia('headimg_' . $account['acid'] . '.jpg') . '?time=' . time() : './resource/images/nopic-107.png';
		}
	}
	iajax(0, $account_list);
}