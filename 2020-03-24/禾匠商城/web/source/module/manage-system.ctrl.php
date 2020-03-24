<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('extension');
load()->model('cloud');
load()->model('cache');
load()->model('module');
load()->model('user');
load()->model('account');
load()->object('cloudapi');
load()->model('utility');
load()->func('db');
load()->model('store');
$dos = array('subscribe', 'check_subscribe', 'check_upgrade', 'get_local_upgrade_info', 'get_upgrade_info', 'upgrade',
			'install', 'installed', 'not_installed', 'uninstall', 'save_module_info', 'module_detail',
			'change_receive_ban', 'install_success', 'set_site_welcome_module',
			'founder_update_modules', 'recycle', 'recycle_post', 'init_modules_logo',
);
$do = in_array($do, $dos) ? $do : 'installed';


	if (user_is_vice_founder() && !empty($_GPC['system_welcome'])) {
		itoast('无权限操作！');
	}
	if ('set_site_welcome_module' == $do) {
		if (!$_W['isfounder']) {
			iajax(1, '非法操作');
		}
		if (!empty($_GPC['name'])) {
			$site = WeUtility::createModuleSystemWelcome($_GPC['name']);
			if (!method_exists($site, 'systemWelcomeDisplay')) {
				iajax(1, '应用未实现系统首页功能！');
			}
		}
		setting_save(trim($_GPC['name']), 'site_welcome_module');
		iajax(0);
	}


$permission_check = array(
	'see_module_manage_system_install' => permission_check_account_user('see_module_manage_system_install') ? 1 : 0,
	'see_module_manage_system_shopinfo' => permission_check_account_user('see_module_manage_system_shopinfo') ? 1 : 0,
	'see_module_manage_system_stop' => permission_check_account_user('see_module_manage_system_stop') ? 1 : 0,
);

if ('subscribe' == $do) {
	$module_uninstall_total = module_uninstall_total($module_support);

	$module_list = user_modules($_W['uid']);
	$subscribe_type = ext_module_msg_types();

	$subscribe_module = array();
	$receive_ban = $_W['setting']['module_receive_ban'];

	if (is_array($module_list) && !empty($module_list)) {
		foreach ($module_list as $module) {
			if (!empty($module['subscribes']) && is_array($module['subscribes'])) {
				$subscribe_module[$module['name']]['subscribe'] = $module['subscribes'];
				$subscribe_module[$module['name']]['title'] = $module['title'];
				$subscribe_module[$module['name']]['name'] = $module['name'];
				$subscribe_module[$module['name']]['subscribe_success'] = 2;
				$subscribe_module[$module['name']]['receive_ban'] = in_array($module['name'], $receive_ban) ? 1 : 0;
			}
		}
	}
}

if ('check_subscribe' == $do) {
	$module = module_fetch($_GPC['module_name']);
	if (empty($module)) {
		iajax(1);
	}
	$obj = WeUtility::createModuleReceiver($module['name']);
	if (empty($obj)) {
		iajax(1);
	}
	$obj->uniacid = $_W['uniacid'];
	$obj->acid = $_W['acid'];
	$obj->message = array(
		'event' => 'subscribe',
	);
	if (method_exists($obj, 'receive')) {
		@$obj->receive();
		iajax(0);
	} else {
		iajax(1);
	}
}

if ('get_local_upgrade_info' == $do) {
	$modulename = safe_gpc_string($_GPC['name']);
	$module = module_fetch($modulename);
	if (empty($module)) {
		iajax(1, '模块不存在！');
	}
	$manifest = ext_module_manifest($modulename);
	if (!empty($manifest) && version_compare($manifest['application']['version'], $module['version'], '>')) {
		$local_result = array(
			'name' => $modulename,
			'upgrade' => true,
			'site_branch' => array(),
			'branches' => array(),
			'new_branch' => false,
			'from' => 'local',
			'best_version' => $manifest['application']['version'],
		);
	}
	iajax(0, $local_result);
}

if ('get_upgrade_info' == $do) {
	$modulename = safe_gpc_string($_GPC['name']);
	$module = module_fetch($modulename);
	if (empty($module)) {
		iajax(1, '模块不存在！');
	}
	$manifest_cloud = cloud_m_upgradeinfo($modulename);
	if (is_error($manifest_cloud)) {
		iajax(1, $manifest_cloud['message']);
	}
	$result = array(
		'name' => $modulename,
		'upgrade' => $manifest_cloud['upgrade'],
		'site_branch' => $manifest_cloud['site_branch'],
		'new_branch' => $manifest_cloud['new_branch'],
		'branches' => !empty($manifest_cloud['branches']) ? $manifest_cloud['branches'] : '',
		'from' => 'cloud',
		'id' => $manifest_cloud['id'],
		'system_shutdown' => $manifest_cloud['system_shutdown'],
		'system_shutdown_delay_time' => date('Y-m-d', $manifest_cloud['system_shutdown_delay_time']),
		'can_update' => isset($manifest_cloud['can_update']) ? $manifest_cloud['can_update'] : -1,
	);
	iajax(0, $result);
}

if ('check_upgrade' == $do) {
	$module_upgrade = module_upgrade_info();
	if (is_error($module_upgrade)) {
		iajax(-1, $module_upgrade['message']);
	}
	cache_build_uninstalled_module();

	iajax(0, $module_upgrade);
}

if ('upgrade' == $do) {
	$module_name = safe_gpc_string(trim($_GPC['module_name']));
	$has_new_support = safe_gpc_boolean($_GPC['has_new_support']); 		$module = table('modules')->getByName($module_name);
	if (empty($module)) {
		itoast('模块已经被卸载或是不存在！', '', 'error');
	}

	$manifest = ext_module_manifest($module_name);

		if (empty($manifest)) {
		$cloud_prepare = cloud_prepare();
		if (is_error($cloud_prepare)) {
			iajax(1, $cloud_prepare['message']);
		}
		$module_info = cloud_m_upgradeinfo($module_name);
		if (is_error($module_info)) {
			iajax(1, $module_info);
		}
		if (!empty($_GPC['flag'])) {
			define('ONLINE_MODULE', true);
			$packet = cloud_m_build($module_name, 'upgrade');
			if (is_error($packet)) {
				$extend_button = array();
				if ($packet['errno'] == -3) {
					$extend_button[] = array('url' => "http://s.w7.cc/module-{$packet['cloud_id']}.html", 'title' => '去续费', 'target' => '_blank');
				}
				message($packet['message'], url('module/manage-system', array('support' => $module_support_name)), 'error', true, $extend_button);
			}
			$manifest = ext_module_manifest_parse($packet['manifest']);
		}
	}
	if (empty($manifest)) {
		itoast('模块安装配置文件不存在或是格式不正确！', '', 'error');
	}
	$check_manifest_result = ext_manifest_check($module_name, $manifest);
	if (is_error($check_manifest_result)) {
		itoast($check_manifest_result['message'], '', 'error');
	}

	$check_file_result = ext_file_check($module_name, $manifest);
	if (is_error($check_file_result)) {
		itoast($check_file_result['message'], '', 'error');
	}
		if ($has_new_support && empty($_GPC['upgrade_flag'])) {
		$module_group = uni_groups();
		template('system/module-group');
		exit;
	}

	if (!empty($manifest['platform']['plugin_list'])) {
		pdo_delete('modules_plugin', array('main_module' => $manifest['application']['identifie']));
		foreach ($manifest['platform']['plugin_list'] as $plugin) {
			pdo_insert('modules_plugin', array('main_module' => $manifest['application']['identifie'], 'name' => $plugin));
		}
	}

	$module_upgrade = ext_module_convert($manifest);
	unset($module_upgrade['name'], $module_upgrade['title'], $module_upgrade['ability'], $module_upgrade['description']);

		$points = ext_module_bindings();
	$bindings = array_elements(array_keys($points), $module_upgrade, false);
	foreach ($points as $point_name => $point_info) {
		unset($module_upgrade[$point_name]);
		if (is_array($bindings[$point_name]) && !empty($bindings[$point_name])) {
			foreach ($bindings[$point_name] as $entry) {
				$entry['module'] = $manifest['application']['identifie'];
				$entry['entry'] = $point_name;
				if ('page' == $point_name && !empty($wxapp_support)) {
					$entry['url'] = $entry['do'];
					$entry['do'] = '';
				}
				if ($entry['title'] && $entry['do']) {
										$not_delete_do[] = $entry['do'];
					$module_binding = table('modules_bindings')->getByEntryDo($module_name, $point_name, $entry['do']);
					if (!empty($module_binding)) {
						pdo_update('modules_bindings', $entry, array('eid' => $module_binding['eid']));
						continue;
					}
				} elseif ($entry['call']) {
					$not_delete_call[] = $entry['call'];
					$module_binding = table('modules_bindings')->getByEntryCall($module_name, $point_name, $entry['call']);
					if (!empty($module_binding)) {
						pdo_update('modules_bindings', $entry, array('eid' => $module_binding['eid']));
						continue;
					}
				}
				pdo_insert('modules_bindings', $entry);
			}
						$modules_bindings_table = table('modules_bindings');
			$modules_bindings_table
				->searchWithModuleEntry($manifest['application']['identifie'], $point_name)
				->where('call', '')
				->where('do !=', empty($not_delete_do) ? '' : $not_delete_do)
				->delete();
						$modules_bindings_table
				->searchWithModuleEntry($manifest['application']['identifie'], $point_name)
				->where('do', '')
				->where('title', '')
				->where('call !=', empty($not_delete_call) ? '' : $not_delete_call)
				->delete();
			unset($not_delete_do, $not_delete_call);
		} else {
			table('modules_bindings')->searchWithModuleEntry($manifest['application']['identifie'], $point_name)->delete();
		}
	}

	if ($packet['schemes']) {
		foreach ($packet['schemes'] as $remote) {
			$remote['tablename'] = trim(tablename($remote['tablename']), '`');
			$local = db_table_schema(pdo(), $remote['tablename']);
			$sqls = db_table_fix_sql($local, $remote);
			foreach ($sqls as $sql) {
				pdo_run($sql);
			}
		}
	}

	ext_module_run_script($manifest, 'upgrade');

	$module_upgrade['permissions'] = iserializer($module_upgrade['permissions']);
	if (!empty($module_info['version']['cloud_setting'])) {
		$module_upgrade['settings'] = 2;
	} else {
		$module_upgrade['settings'] = empty($module_upgrade['settings']) ? 0 : 1;
	}

	if (!empty($_GPC['support'])) {
		$support = explode(',', $_GPC['support']);
	}

	if ($has_new_support) {
		$module_upgrade['cloud_record'] = STATUS_ON;
		foreach ($module_all_support as $support_name => $info) {
			if (!in_array($support_name, $support)) {
				$module_upgrade[$support_name] = $module[$support_name];
			}
		}
	}

	pdo_update('modules', $module_upgrade, array('name' => $module_name));

	$post_groups = $_GPC['group'];
	if ($_GPC['upgrade_flag'] && !empty($post_groups)) {
		$module_upgrade['name'] = $module_name;
		foreach ($post_groups as $groupid) {
			foreach ($support as $val) {
				module_add_to_uni_group($module_upgrade, $groupid, $val);
			}
		}
	}

	cache_build_account_modules();
	if (!empty($module_upgrade['subscribes'])) {
		ext_check_module_subscribe($module_name);
	}
	cache_delete(cache_system_key('cloud_transtoken'));
	cache_build_module_info($module_name);
	cache_build_uni_group();
	if ($has_new_support) {
		
			foreach ($module_all_support as $support_name => $info) {
				if ($module_upgrade[$support_name] == $info['support']) {
					module_delete_store_wish_goods($module_name, $support_name);
				}
			}
		
		itoast('模块安装成功！', url('module/manage-system/installed'), 'success');
	} else {
		itoast('模块更新成功！', url('module/manage-system/installed'), 'success');
	}
}

if ('install' == $do) {
	if (empty($_W['isfounder'])) {
		itoast('您没有安装模块的权限', '', 'error');
	}
	$module_name = safe_gpc_string(trim($_GPC['module_name']));
	$installed_module = table('modules')->getByName($module_name);
	if (!empty($_GPC['install_module_support'])) {
		$module_support_name = $_GPC['install_module_support'];
	}
	$manifest = ext_module_manifest($module_name);
	$module_is_cloud = true;
	if (!empty($manifest)) {
		$module_is_cloud = false;
		$result = cloud_m_prepare($module_name);
		if (is_error($result)) {
			itoast($result['message'], referer(), 'error');
		}
		if (!empty($installed_module)) {
			$has_new_support = module_check_notinstalled_support($installed_module, $manifest['platform']['supports']);
			if (empty($has_new_support)) {
				itoast('模块已经安装或是唯一标识已存在！', '', 'error');
			} else {
				header('location: ' . url('module/manage-system/upgrade', array('support' => $module_support_name, 'module_name' => $module_name, 'has_new_support' => 1)));
				exit;
			}
		}
	} else {
		$result = cloud_prepare();
		if (is_error($result)) {
			itoast($result['message'], url('cloud/profile'), 'error');
		}
		$module_info = cloud_m_info($module_name);
		if (is_error($module_info)) {
			itoast($module_info['message'], '', 'error');
		}
		$packet = cloud_m_build($module_name, 'install');
		if (is_error($packet)) {
			$extend_button = array();
			if ($packet['errno'] == -3) {
				$extend_button[] = array('url' => "http://s.w7.cc/module-{$packet['cloud_id']}.html", 'title' => '去续费', 'target' => '_blank');
			}
			message($packet['message'], '', 'error', true, $extend_button);
		}
		$manifest = ext_module_manifest_parse($packet['manifest']);
		if (empty($manifest)) {
			itoast('模块安装配置文件不存在或是格式不正确，请刷新重试！', referer(), 'error');
		}
		if (!empty($installed_module)) {
			$has_new_support = module_check_notinstalled_support($installed_module, $manifest['platform']['supports']);
			if (empty($has_new_support)) {
				itoast('模块已经安装或是唯一标识已存在！', '', 'error');
			} else {
				header('location: ' . url('cloud/process', array('support' => $module_support_name, 'm' => $module_name, 'is_upgrade' => 1, 'has_new_support' => 1)));
				exit;
			}
		}
		if (empty($_GPC['flag'])) {
			header('location: ' . url('cloud/process', array('support' => $module_support_name, 'm' => $module_name)));
			exit;
		} else {
			define('ONLINE_MODULE', true);
		}
	}
	if (!empty($manifest['platform']['main_module'])) {
		$main_module_fetch = module_fetch($manifest['platform']['main_module']);
		if (empty($main_module_fetch)) {
			itoast('请先安装主模块后再安装插件', url('module/manage-system/installed'), 'error', array(array('title' => '查看主程序', 'url' => url('module/manage-system/module_detail', array('name' => $manifest['platform']['main_module'])))));
		}
		$plugin_exist = table('modules_plugin')->getPluginExists($manifest['platform']['main_module'], $manifest['application']['identifie']);
		if (empty($plugin_exist)) {
			pdo_insert('modules_plugin', array('main_module' => $manifest['platform']['main_module'], 'name' => $manifest['application']['identifie']));
		}
	}

	$check_manifest_result = ext_manifest_check($module_name, $manifest);
	if (is_error($check_manifest_result)) {
		itoast($check_manifest_result['message'], '', 'error');
	}
	$check_file_result = ext_file_check($module_name, $manifest);
	if (is_error($check_file_result)) {
		itoast('模块缺失文件，请检查模块文件中site.php, processor.php, module.php, receiver.php 文件是否存在！', '', 'error');
	}

	$module = ext_module_convert($manifest);

	if (file_exists(IA_ROOT . '/addons/' . $module['name'] . '/icon-custom.jpg')) {
		$module['logo'] = 'addons/' . $module['name'] . '/icon-custom.jpg';
	} else {
		$module['logo'] = 'addons/' . $module['name'] . '/icon.jpg';
	}
		if (!$_W['ispost'] || empty($_GPC['flag'])) {
		$module_group = uni_groups();
		template('system/module-group');
		exit;
	}
	if (!empty($manifest['platform']['plugin_list'])) {
		foreach ($manifest['platform']['plugin_list'] as $plugin) {
			pdo_insert('modules_plugin', array('main_module' => $manifest['application']['identifie'], 'name' => $plugin));
		}
	}
	$post_groups = $_GPC['group'];
	$points = ext_module_bindings();
	if (!empty($points)) {
		$bindings = array_elements(array_keys($points), $module, false);
		table('modules_bindings')->deleteByName($manifest['application']['identifie']);
		foreach ($points as $name => $point) {
			unset($module[$name]);
			if (is_array($bindings[$name]) && !empty($bindings[$name])) {
				foreach ($bindings[$name] as $entry) {
					$entry['module'] = $manifest['application']['identifie'];
					$entry['entry'] = $name;
					if ('page' == $name && !empty($wxapp_support)) {
						$entry['url'] = $entry['do'];
						$entry['do'] = '';
					}
					table('modules_bindings')->fill($entry)->save();
				}
			}
		}
	}

	$module['permissions'] = iserializer($module['permissions']);

	$module_subscribe_success = true;
	if (!empty($module['subscribes'])) {
		$subscribes = iunserializer($module['subscribes']);
		if (!empty($subscribes)) {
			$module_subscribe_success = ext_check_module_subscribe($module['name']);
		}
	}

	if (!empty($module_info['version']['cloud_setting'])) {
		$module['settings'] = 2;
	}

	$module['title_initial'] = get_first_pinyin($module['title']);

	if ($packet['schemes']) {
		foreach ($packet['schemes'] as $remote) {
			$remote['tablename'] = trim(tablename($remote['tablename']), '`');
			$local = db_table_schema(pdo(), $remote['tablename']);
			$sqls = db_table_fix_sql($local, $remote);
			foreach ($sqls as $sql) {
				pdo_run($sql);
			}
		}
	}

	ext_module_run_script($manifest, 'install');

	$module_support_name_arr = explode(',', $module_support_name);
	foreach ($module_all_support as $support => $value) {
		if (!in_array($support, $module_support_name_arr)) {
			$module[$support] = $value['not_support'];
		}
	}

	$module_store_goods_info = pdo_get('site_store_goods', array('module' => $module_name));
	if (!empty($module_store_goods_info) && 1 == $module_store_goods_info['is_wish']) {
		$module['title'] = $module_store_goods_info['title'];
		$module['title_initial'] = get_first_pinyin($module_store_goods_info['title']);
		$module['logo'] = $module_store_goods_info['logo'];
	}

	if (!$module_is_cloud) {
		$module['from'] = 'local';
	}

	if (pdo_insert('modules', $module)) {
		if ($_GPC['flag'] && !empty($post_groups) && $module['name']) {
			foreach ($post_groups as $groupid) {
				foreach ($module_support_name_arr as $support_name) {
					module_add_to_uni_group($module, $groupid, $support_name);
				}
			}
		}
		
			foreach ($module_all_support as $support => $value) {
				if ($module[$support] == $value['support']) {
					module_delete_store_wish_goods($module_name, $support);
				}
			}
		
		$store_goods_id = pdo_getcolumn('site_store_goods', array('module' => $module['name'], 'is_wish' => 1), 'id');
		if (!empty($store_goods_id)) {
			$store_goods_orders = pdo_getall('site_store_order', array('goodsid' => $store_goods_id));
		}
		if (!empty($store_goods_orders)) {
			foreach ($store_goods_orders as $store_order_info) {
				cache_build_account_modules($store_order_info['uniacid']);
			}
		}
		cache_build_module_subscribe_type();
		cache_build_module_info($module_name);
		cache_build_uni_group();
		cache_delete(cache_system_key('user_modules', array('uid' => $_W['uid'])));
		if (MODULE_SUPPORT_SYSTEMWELCOME_NAME == $module_support_name) {
			itoast('模块安装成功！', url('module/manage-system/install_success', array('support' => $module_support_name)), 'success');
		}
		itoast('模块安装成功！', url('module/manage-system/install_success'), 'success');
	} else {
		itoast('模块安装失败, 请联系模块开发者！');
	}
}

if ('change_receive_ban' == $do) {
	$modulename = trim($_GPC['modulename']);
	$module_exist = module_fetch($modulename);
	if (empty($module_exist)) {
		iajax(1, '模块不存在', '');
	}
	if (!is_array($_W['setting']['module_receive_ban'])) {
		$_W['setting']['module_receive_ban'] = array();
	}
	if (in_array($modulename, $_W['setting']['module_receive_ban'])) {
		unset($_W['setting']['module_receive_ban'][$modulename]);
	} else {
		$_W['setting']['module_receive_ban'][$modulename] = $modulename;
	}
	setting_save($_W['setting']['module_receive_ban'], 'module_receive_ban');
	cache_build_module_subscribe_type();
	cache_build_module_info($modulename);
	iajax(0, '');
}

if ('save_module_info' == $do) {
	$module_name = trim($_GPC['modulename']);
	if (empty($module_name)) {
		iajax(1, '数据非法');
	}
	$module = module_fetch($module_name);
	if (empty($module)) {
		iajax(1, '数据非法');
	}
	$module_info_type = key($_GPC['moduleinfo']);
	$module_icon_map = array(
		'logo' => array(
			'filename' => 'icon-custom.jpg',
			'url' => trim($_GPC['moduleinfo']['logo']),
		),
		'preview' => array(
			'filename' => 'preview-custom.jpg',
			'url' => trim($_GPC['moduleinfo']['preview']),
		),
	);

	$module_field = array('title', 'ability', 'description', 'logo');
	if (!isset($module_icon_map[$module_info_type]) && !in_array($module_info_type, $module_field)) {
		iajax(1, '数据非法');
	}
	if (in_array($module_info_type, $module_field)) {
		$module_update = array($module_info_type => trim($_GPC['moduleinfo'][$module_info_type]));
		if ('title' == $module_info_type) {
			$module_update['title_initial'] = get_first_pinyin($_GPC['moduleinfo']['title']);
		}

		if ('logo' == $module_info_type) {
			$module_update['logo'] = trim($_GPC['moduleinfo']['logo']);
		}
		$result = pdo_update('modules', $module_update, array('name' => $module_name));
	}

	if (in_array($module_info_type, array('logo', 'preview'))) {
		$image_destination_url = IA_ROOT . '/addons/' . $module_name . '/' . $module_icon_map[$module_info_type]['filename'];
		$result = utility_image_rename($module_icon_map[$module_info_type]['url'], $image_destination_url);
	}

	
		if (!empty($module_update['title']) || !empty($module_update['logo'])) {
			update_wish_goods_info($module_update, $module_name);
		}
	

	cache_build_module_info($module_name);
	if (!empty($result)) {
		iajax(0, '');
	}
	iajax(1, '更新失败');
}

if ('module_detail' == $do) {
	$module_name = trim($_GPC['name']);
	$module_info = module_fetch($module_name);
	if (empty($module_info)) {
		itoast('模块不存在或是已经被删除', '', 'error');
	}

	$manifest = ext_module_manifest($module_name);
	if (empty($manifest)) {
		$current_cloud_module = cloud_m_info($module_name);
		$module_info['cloud_mid'] = !empty($current_cloud_module['id']) ? $current_cloud_module['id'] : '';
		if (!is_error($current_cloud_module) && user_is_founder($_W['uid'], true)) {
			$module_info['service_expiretime'] = date('Y-m-d H:i:s', $current_cloud_module['service_expiretime']);
			$module_info['service_expire'] = $current_cloud_module['service_expiretime'] > time() ? 0 : 1;
		}
	}

		foreach ($module_info as $key => $value) {
		if ($key != $module_support . '_support' && strexists($key, '_support') && MODULE_SUPPORT_ACCOUNT == $value) {
			$module_info['relation'][] = $key;
		}
	}

	if (!empty($module_info['main_module'])) {
		$main_module = module_fetch($module_info['main_module']);
	}
	if (!empty($module_info['plugin_list'])) {
		$module_info['plugin_list'] = module_get_plugin_list($module_name);
	}
	$module_group_list = pdo_getall('uni_group', array('uniacid' => 0, 'uid' => 0));
	$module_group = array();
	if (!empty($module_group_list)) {
		foreach ($module_group_list as $group) {
			if (user_is_vice_founder() && $group['owner_uid'] != $_W['uid']) {
				continue;
			}
			$group['modules'] = iunserializer($group['modules']);
			if (is_array($group['modules'])) {
				foreach ($group['modules'] as $modulenames) {
					if (is_array($modulenames) && in_array($module_name, $modulenames)) {
						$module_group[] = $group;
						break;
					}
				}
			}
		}
	}
	$subscribes_type = ext_module_msg_types();
}

if ('uninstall' == $do) {
	if (!user_is_founder($_W['uid'], true)) {
		itoast('您没有卸载模块的权限！');
	}
	$name = safe_gpc_string(trim($_GPC['module_name']));
	$module = module_fetch($name, false);
	if (empty($module) || MODULE_SUPPORT_ACCOUNT != $module[$module_support_name]) {
		itoast('应用不存在或是已经卸载！');
	}
	$module[$module_support_name] = MODULE_NONSUPPORT_ACCOUNT;
	$uninstall_all = true;
	foreach ($module_all_support as $support => $value) {
		if (MODULE_SUPPORT_ACCOUNT == $module[$support]) {
			$uninstall_all = false;
			break;
		}
	}
	if ($uninstall_all) {
		if (!isset($_GPC['confirm'])) {
			$message = '';
			if ($module['isrulefields']) {
				$message .= '是否删除相关规则和统计分析数据<div><a class="btn btn-primary" style="width:80px;" href="' . url('module/manage-system/uninstall', array('module_name' => $name, 'confirm' => 1, 'support' => $module_support_name)) . '">是</a> &nbsp;&nbsp;<a class="btn btn-default" style="width:80px;" href="' . url('module/manage-system/uninstall', array('support' => $module_support_name, 'module_name' => $name, 'confirm' => 0)) . '">否</a></div>';
			}
			if (!empty($message)) {
				message($message, '', 'tips');
			}
		}
		ext_module_uninstall($name, $_GPC['confirm']);
		cache_build_module_subscribe_type();
	} else {
		table('modules')->where('mid', $module['mid'])->fill(array($module_support_name => MODULE_NONSUPPORT_ACCOUNT, 'cloud_record' => STATUS_OFF))->save();
		module_cancel_recycle($name, MODULE_RECYCLE_INSTALL_DISABLED, $module_support_name);
	}

	cache_build_account_modules($_W['uniacid'], $_W['uid']);
	cache_build_module_info($name);
	module_upgrade_info();
	if (MODULE_SUPPORT_SYSTEMWELCOME_NAME == $module_support_name) {
		itoast('模块已卸载！', url('module/manage-system/recycle', array('type' => MODULE_RECYCLE_INSTALL_DISABLED, 'support' => $module_support_name)), 'success');
	}
	itoast('模块已卸载！', url('module/manage-system/recycle', array('type' => MODULE_RECYCLE_INSTALL_DISABLED)), 'success');
}

if ('recycle_post' == $do) {
	$name = safe_gpc_string(trim($_GPC['module_name']));
	if (empty($name)) {
		itoast('应用不存在或是已经被删除', referer(), 'error');
	}
	if (!empty($_GPC['supports'])) {
		$supports = safe_gpc_array($_GPC['supports']);
	} else {
		$supports = array(safe_gpc_string($_GPC['support']));
	}

	$module = table('modules')->getByName($name);
	$recycle_table = table('modules_recycle');
	foreach ($supports as $support) {
		if (!in_array($support, array_keys($module_all_support))) {
			continue;
		}
		$recycle_table->searchWithSupport($support);
		if (!empty($module[$support]) && 2 == $module[$support]) {
						$module_recycle = $recycle_table->searchWithNameType($name, 1)->get();
			if (empty($module_recycle)) {
				$msg = '模块已停用!';
				module_recycle($name, MODULE_RECYCLE_INSTALL_DISABLED, $support);
			} else {
				$msg = '模块已恢复!';
				module_cancel_recycle($name, MODULE_RECYCLE_INSTALL_DISABLED, $support);
			}
			cache_write(cache_system_key('user_modules', array('uid' => $_W['uid'])), array());
			cache_build_module_info($name);
		} else {
						$module_recycle = $recycle_table->searchWithNameType($name, 2)->get();
			if (empty($module_recycle)) {
				$msg = '模块已放入回收站!';
				module_recycle($name, MODULE_RECYCLE_UNINSTALL_IGNORE, $support);
			} else {
				$msg = '模块已恢复!';
				module_cancel_recycle($name, MODULE_RECYCLE_UNINSTALL_IGNORE, $support);
			}
		}
	}
	itoast($msg, referer(), 'success');
}

if ('recycle' == $do) {
	$type = intval($_GPC['type']);
	$support = safe_gpc_string($_GPC['support']);
	$support = empty($support) ? 'all' : $support;
	$title = safe_gpc_string($_GPC['title']);
	$letter = safe_gpc_string($_GPC['letter']);

	$pageindex = max($_GPC['page'], 1);
	$pagesize = 15;

	$module_recycle_table = table('modules_recycle');

	$fields = 'all' == $support ? 'a.title, a.title_initial, a.logo, a.version, b.*' : 'a.*, b.type';
	if (MODULE_RECYCLE_INSTALL_DISABLED == $type) {
		$module_recycle_table->searchWithModules($fields);
	} else {
		$fields .= 'all' == $support ? ', a.cloud_id' : '';
		$module_recycle_table->searchWithModulesCloud($fields);
	}

	$module_recycle_table->where('b.type', $type);
	if ('all' != $support) {
		$module_recycle_table->where("b.{$support}", 1);
	}

	if (!empty($title)) {
		$module_recycle_table->where('a.title LIKE', "%{$title}%");
	}

	if (!empty($letter) && 1 == strlen($letter)) {
		$module_recycle_table->where('a.title_initial', $letter);
	}

	$modulelist = $module_recycle_table->getall();
	if (!empty($modulelist)) {
		$modulelist_recycle = array();
		foreach ($modulelist as $modulename => &$module) {
			$module_recycle_info = $module;
			$module = module_fetch($module['name'], false);
			if (empty($module)) {
				$module = table('modules_cloud')->getByName($module_recycle_info['name']);
			}
			$module['cloud_id'] = $module_recycle_info['cloud_id'];
			foreach ($module_all_support as $type_key => $type_val) {
				if ('all' == $support) {
					if (1 == $module_recycle_info[$type_key] && MODULE_SUPPORT_SYSTEMWELCOME_NAME != $type_key) {
						$module['support'] = $module_recycle_info['support'] = $type_key;
						$module['support_name'] = $module_recycle_info['support_name'] = $type_val['type_name'];
						$module_recycle_info['logo'] = $module['logo'];
						$modulelist_recycle[$module_recycle_info['name'] . '_' . $type_key] = $module_recycle_info;
					}
				} else {
					$module['support'] = $support;
					$module['support_name'] = $module_all_support[$support]['type_name'];
				}
			}
		}
		unset($module);
	}

	if ('all' == $support) {
		$modulelist = $modulelist_recycle;
	}
	$total = count((array)$modulelist);
	$pager = pagination($total, $pageindex, $pagesize, '', array('ajaxcallback' => true, 'callbackfuncname' => 'loadMore'));

	$module_uninstall_total = module_uninstall_total($module_support);
}

if ('installed' == $do) {
	$module_list = module_installed_list($module_support);
	if (!empty($module_list)) {
		foreach ($module_list as $key => &$module) {
			if (!empty($module['issystem'])) {
				unset($module_list[$key]);
			}
			if ('all' != $module_support) {
				$module['support_name'] = $module_all_support[$module_support_name]['type_name'];
			}
		}
		unset($module);
	}
		if ('all' == $module_support) {
		$module_list_support = array();
		if (!empty($module_list)) {
			foreach ($module_list as $key => &$module) {
				foreach ($module_all_support as $module_support_type => $module_support_val) {
					if (MODULE_SUPPORT_SYSTEMWELCOME_NAME == $module_support_type) {
						continue;
					}
					if ($module[$module_support_type] == $module_support_val['support']) {
						$module['support'] = $module_support_type;
						$module['support_name'] = $module_support_val['type_name'];
						$module_list_support[$module['name'] . '_' . $module_support_type] = $module;
					}
				}
			}
			unset($module);
		}
		$module_list = $module_list_support;
	}

	$pager = pagination(count($module_list), 1, 15, '', array('ajaxcallback' => true, 'callbackfuncname' => 'loadMore'));
	$module_uninstall_total = module_uninstall_total($module_support);
}

if ('not_installed' == $do) {
	$title = safe_gpc_string($_GPC['title']);
	$letter = safe_gpc_string($_GPC['letter']);

	cache_build_uninstalled_module();

	$module_cloud_table = table('modules_cloud');

	if (!empty($title)) {
		$module_cloud_table->where('title LIKE', "%{$title}%");
	}
	if (!empty($letter) && 1 == strlen($letter)) {
		$module_cloud_table->where('title_initial', $letter);
	}
	if ('all' != $module_support) {
		$module_cloud_table->where($module_support . '_support', 2);
	}
	$module_cloud_table->where('install_status', array(MODULE_LOCAL_UNINSTALL, MODULE_CLOUD_UNINSTALL));
	$module_cloud_table->orderby('install_status', 'asc');
	$modulelist = $module_cloud_table->getall();

	if (!empty($modulelist)) {
				$modulenames = array();
		foreach ($modulelist as $module) {
			if (!empty($module['name']) && !in_array($module['name'], $modulenames)) {
				$modulenames[] = $module['name'];
			}
		}
		$module_recycle_support = array();
		if ($modulenames) {
			$modules_recycle = table('modules_recycle')->getByName($modulenames, '');
			if (!empty($modules_recycle)) {
				foreach ($modules_recycle as $info) {
					foreach ($module_all_support as $support => $value) {
						if (empty($module_recycle_support[$info['name']][$support])) {
							$module_recycle_support[$info['name']][$support] = $info[$support];
						}
					}
				}
			}
		}
				foreach ($modulelist as $key => $module) {
			$is_unset = true;
			foreach ($module_all_support as $support => $value) {
				if (!empty($module_recycle_support[$module['name']][$support])) {
					$module[$support] = $value['not_support'];
				}
				if ('all' == $module_support) {
					$module[MODULE_SUPPORT_SYSTEMWELCOME_NAME] = $value['not_support'];
					$modulelist[$key][MODULE_SUPPORT_SYSTEMWELCOME_NAME] = $value['not_support'];
				}
				if ($module[$support] == $value['support']) {
					$is_unset = false;
				}
			}
			if ($is_unset) {
				unset($modulelist[$key]);
			}
		}
	}
	$module_uninstall_total = module_uninstall_total($module_support);
	$pager = pagination(count($modulelist), 1, 15, '', array('ajaxcallback' => true, 'callbackfuncname' => 'loadMore'));
}

if ('init_modules_logo' == $do) {
	if (!pdo_fieldexists('modules', 'logo')) {
		pdo_query('ALTER TABLE ' . tablename('modules') . " ADD `logo` varchar(250) NOT NULL DEFAULT '' ;");
	}
	$modules = pdo_fetchall('SELECT name FROM ' . tablename('modules') . ' WHERE issystem!=1');
	foreach ($modules as $key => $val) {
		if (file_exists(IA_ROOT . '/addons/' . $val['name'] . '/icon-custom.jpg')) {
			$val['logo'] = 'addons/' . $val['name'] . '/icon-custom.jpg';
		} else {
			$val['logo'] = 'addons/' . $val['name'] . '/icon.jpg';
		}
		pdo_update('modules', array('logo' => $val['logo']), array('name' => $val['name']));
	}
	iajax(0, '更新成功', url('module/manage-system/installed'));
}

template('module/manage-system');