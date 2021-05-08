<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('switch');
load()->model('miniapp');

$dos = array('display', 'switch', 'have_permission_uniacids', 'accounts_dropdown_menu', 'rank', 'set_default_account', 'switch_last_module', 'init_uni_modules', 'own');
$do = in_array($do, $dos) ? $do : 'display';

if ('switch_last_module' == $do) {
	$last_module = switch_get_module_display();
	if (!empty($last_module)) {
		$account_info = uni_fetch($last_module['uniacid']);
		if (!is_error($account_info) && ($account_info['endtime'] > 0 && TIMESTAMP > $account_info['endtime'] && !user_is_founder($_W['uid'], true))) {
			itoast('', url('account/display/switch', array('module_name' => $last_module['modulename'], 'uniacid' => $last_module['uniacid'], 'switch_uniacid' => 1, 'tohome' => intval($_GPC['tohome']))));
		}
	}
	if ($_W['isfounder']) {
		$do = 'display';
	} else {
		itoast('', $_W['siteroot'] . 'web/home.php', 'info');
	}
}
if ('display' == $do) {
	itoast('', $_W['siteroot'] . 'web/home.php');
}
if ('display' == $do) {
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize = 20;

	$uni_modules_table = table('uni_modules');
	$module_title = safe_gpc_string($_GPC['module_title']);
	$module_letter = safe_gpc_string($_GPC['letter']);

	$uni_modules_table->searchGroupbyModuleName();
	$own_account_modules = array();

	$own_account_modules = $uni_modules_table->getModulesByUid($_W['uid']);

	$user_lastuse_table = table('users_lastuse');
	$user_lastuse_table->searchWithoutType('account_display');
	$user_lastuse_table->searchWithoutType('module_display');
	$default_module_list = $user_lastuse_table->getDefaultModulesAccount($_W['uid']);

	$modules_rank_table = table('modules_rank');
	$modules_rank_list = $modules_rank_table->getAllByUid($_W['uid']);

	$module_support_types = module_support_type();
	foreach ($own_account_modules['modules'] as $account_module_name => &$account_module_info) {
		if (ACCOUNT_MANAGE_NAME_CLERK == $account_module_info['role'] || ACCOUNT_MANAGE_NAME_OPERATOR == $account_module_info['role'] || ACCOUNT_MANAGE_NAME_MANAGER == $account_module_info['role']) {
			$user_permission_table = table('users_permission');
			$operator_modules_permissions = $user_permission_table->getAllUserModulePermission($_W['uid'], $account_module_info['uniacid']);
						$user_module_permission_info = $user_permission_table->getUserPermissionByType($_W['uid'], $account_module_info['uniacid'], $account_module_info['module_name']);
			if (!$user_module_permission_info && !empty($operator_modules_permissions)) {
				unset($own_account_modules['modules'][$account_module_name]);
			}
		}

		$uni_module_info = module_fetch($account_module_info['module_name']);
		if (empty($uni_module_info)) {
			unset($own_account_modules['modules'][$account_module_name]);
			continue;
		}

		if (in_array($account_module_info['module_name'], array_keys($modules_rank_list))) {
			$account_module_info['rank'] = $modules_rank_list[$account_module_info['module_name']]['rank'];
		}

		if (in_array($account_module_info['module_name'], array_keys($own_account_modules['modules']))) {
			$account_module_info['default_uniacid'] = $default_module_list[$account_module_info['module_name']]['default_uniacid'];
		}

		if (ACCOUNT_MANAGE_NAME_CLERK == $_W['highest_role']) {
			$account_module_info['uniacid'] = $account_module_info['permission_uniacid'];
			$account_module_info['default_uniacid'] = $account_module_info['permission_uniacid'];
		}

		$uni_account_info = uni_fetch($account_module_info['uniacid']);
		$account_module_info['account_name'] = $uni_account_info['name'];
		$account_module_info['account_type'] = $uni_account_info['account_type'];
		$account_module_info['account_logo'] = $uni_account_info['logo'];

		$account_module_info['logo'] = tomedia($uni_module_info['logo']);
		$account_module_info['title'] = $uni_module_info['title'];
		$account_module_info['title_initial'] = $uni_module_info['title_initial'];

		foreach ($module_support_types as $support_type => $support_info) {
			$account_module_info[$support_type] = $uni_module_info[$support_type];
		}

		if (!empty($account_module_info['default_uniacid'])) {
			$account_module_info['default_account_name'] = $default_module_list[$account_module_info['module_name']]['default_account_name'];
			$account_module_info['default_account_info'] = uni_fetch($account_module_info['default_uniacid']);
			$account_module_info['default_account_type'] = $account_module_info['default_account_info']['type'];
			$account_module_info['default_account_logo'] = $account_module_info['default_account_info']['logo'];
		}
	}
	unset($account_module_info);

		$sort_arr = array();
	foreach ($own_account_modules['modules'] as $sort_key => $sort_val) {
		$sort_arr[$sort_key] = $sort_val['rank'];
	}
	array_multisort($sort_arr, SORT_DESC, $own_account_modules['modules']);

		$own_account_modules['system_have_modules'] = table('modules')->where('issystem !=', 1)->get();
	template('module/display');
}

if ('rank' == $do) {
	$module_name = trim($_GPC['module_name']);
	$uniacid = intval($_GPC['uniacid']);

	$exist = module_fetch($module_name, $uniacid);
	if (empty($exist)) {
		iajax(1, '模块不存在', '');
	}
	module_rank_top($module_name, $uniacid);
	itoast('更新成功！', referer(), 'success');
}

if ('switch' == $do) {
	$module_name = trim($_GPC['module_name']);
	$module_info = module_fetch($module_name);
	$module_name = empty($module_info['main_module']) ? $module_name : $module_info['main_module'];
	$uniacid = intval($_GPC['uniacid']);
	$redirect = safe_gpc_url($_GPC['redirect']);
	$account_info = uni_fetch($uniacid);
	if (empty($module_info)) {
		itoast('模块不存在或已经删除！', referer(), 'error');
	}
	if ($account_info->supportVersion) {
		$miniapp_version_info = miniapp_fetch($uniacid);
		$version_id = $miniapp_version_info['version']['id'];
	}

	if (empty($uniacid) && empty($version_id)) {
		itoast('该模块暂无可用的公众号或小程序，请先给公众号或小程序分配该应用的使用权限', url('module/display'), 'info');
	}

	if (!empty($version_id)) {
		$version_info = miniapp_version($version_id);
		miniapp_update_last_use_version($version_info['uniacid'], $version_id);
		$url = url('account/display/switch', array('uniacid' => $uniacid, 'module_name' => $module_name, 'version_id' => $version_id, 'switch_uniacid' => true, 'redirect' => urlencode($redirect)));
	} else {
		$url = url('account/display/switch', array('uniacid' => $uniacid, 'module_name' => $module_name, 'switch_uniacid' => true, 'redirect' => urlencode($redirect)));
	}

	switch_save_module_display($uniacid, $module_name);
	itoast('', $url, 'success');
}

if ('have_permission_uniacids' == $do) {
	$module_name = trim($_GPC['module_name']);
	$accounts_list = module_link_uniacid_fetch($_W['uid'], $module_name);
	iajax(0, $accounts_list);
}

if ('accounts_dropdown_menu' == $do) {
	$module_name = trim($_GPC['module_name']);
	if (empty($module_name)) {
		exit();
	}
	$accounts_list = module_link_uniacid_fetch($_W['uid'], $module_name);
	if (empty($accounts_list)) {
		exit();
	}

	foreach ($accounts_list as $key => $account) {
		$url = url('module/display/switch', array('uniacid' => $account['uniacid'], 'module_name' => $module_name));
		if (!empty($account['version_id'])) {
			$url .= '&version_id=' . $account['version_id'];
		}
		$accounts_list[$key]['url'] = $url;
	}
	echo template('module/dropdown-menu');
	exit;
}

if ('set_default_account' == $do) {
	$uniacid = intval($_GPC['uniacid']);
	$module_name = safe_gpc_string($_GPC['module_name']);
	if (empty($uniacid) || empty($module_name)) {
		iajax(-1, '设置失败!');
	}
	$result = switch_save_module($uniacid, $module_name);
	if ($result) {
		iajax(0, '设置成功!');
	} else {
		iajax(-1, '设置失败!');
	}
}

if ('init_uni_modules' == $do) {
	$pageindex = max(1, intval($_GPC['pageindex']));
	$pagesize = 20;
	$total = table('account')->count();
	$total = ceil($total / $pagesize);
	$init_accounts = table('account')->searchWithPage($pageindex, $pagesize)->getAll();
	if (empty($init_accounts)) {
		iajax(1, 'finished');
	}
	foreach ($init_accounts as $account) {
		cache_build_account_modules($account['uniacid']);
	}
	$pageindex = $pageindex + 1;
	iajax(0, array('pageindex' => $pageindex, 'total' => $total));
}

if ('own' == $do) {
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize = 24;
	$limit_num = intval($_GPC['limit_num']);
	$pagesize = $limit_num > 0 ? $limit_num : $pagesize;
	$keyword = safe_gpc_string($_GPC['keyword']);
	if (!empty($keyword)) {
		$search_module = table('modules')->select('name')->where('title LIKE', '%'.$keyword.'%')->getall('name');
	}
	$uni_modules_table = table('uni_modules');
	$modules_list = $result =  array();
	$_W['highest_role'] = ACCOUNT_MANAGE_NAME_CLERK == $_GPC['role'] ? ACCOUNT_MANAGE_NAME_CLERK : $_W['highest_role'];
	switch($_W['highest_role']) {
		case ACCOUNT_MANAGE_NAME_MANAGER:
		case ACCOUNT_MANAGE_NAME_OPERATOR:
		case ACCOUNT_MANAGE_NAME_CLERK:
			if (!empty($keyword)) {
				$uni_modules_table->where('u.module_name IN', array_keys($search_module));
			}
			$modules_list = $uni_modules_table->getModulesByUid($_W['uid']);
			if (in_array($_W['highest_role'], array(ACCOUNT_MANAGE_NAME_OPERATOR, ACCOUNT_MANAGE_NAME_MANAGER))) {
				foreach ($modules_list['modules'] as $account_module_name => &$account_module_info) {
					$user_permission_table = table('users_permission');
					$user_module_permission_info = $user_permission_table->getUserPermissionByType($_W['uid'], $account_module_info['uniacid'], $account_module_info['module_name']);
					if (empty($user_module_permission_info)) {
						unset($modules_list['modules'][$account_module_name]);
						continue;
					}
				}
			}
			$modules_list = $modules_list['modules'];
			$modules_list = array_slice($modules_list, ($pageindex - 1) * $pagesize, $pagesize);
			break;
		default:
			$owned_account_list = table('account')->userOwnedAccount();
			if (empty($owned_account_list)) {
				iajax(0, array());
			}

			if (!empty($keyword)) {
				$uni_modules_table->where('module_name IN', array_keys($search_module));
			}
			$uni_modules_table->where('uniacid IN', array_keys($owned_account_list));
			$uni_modules_list = $uni_modules_table->getall();
			$clerk_modules_list = pdo_fetchall("SELECT uau.id, uau.uniacid, up.type module_name FROM " . tablename('uni_account_users') . " as uau LEFT JOIN " . tablename('users_permission') . " as up ON uau.uniacid=up.uniacid AND uau.uid=up.uid WHERE uau.role='clerk' AND uau.uid=" . $_W['uid']);
			$modules_list_all = array_merge($uni_modules_list, $clerk_modules_list);
			$modules_list = array_slice($modules_list_all, ($pageindex - 1) * $pagesize, $pagesize);
			break;
	}
	if (empty($modules_list)) {
		iajax(0, array());
	}

	foreach ($modules_list as $module) {
		$module_info = module_fetch($module['module_name']);
		$module_info['list_type'] = 'module';
		$module_info['is_star'] = table('users_operate_star')->getByUidUniacidModulename($_W['uid'], $module['uniacid'], $module['module_name']) ? 1 : 0;
		$module_info['switchurl'] = url('module/display/switch', array('module_name' => $module['module_name'], 'uniacid' => $module['uniacid']));
		$uni_account_info = uni_fetch($module['uniacid']);
		$module_info['default_account'] = array(
			'name' => $uni_account_info['name'],
			'uniacid' => $uni_account_info['uniacid'],
			'type' => $uni_account_info['type'],
			'logo' => $uni_account_info['logo'],
		);
		$result[] = $module_info;
	}
	iajax(0,$result);
}
