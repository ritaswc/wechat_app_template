<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('module');
load()->model('user');
load()->model('module');

$dos = array('display', 'del', 'post', 'save', 'edit');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';
if (!user_is_founder($_W['uid'])) {
	itoast('无权限操作！', referer(), 'error');
}

if ('display' == $do) {
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize = 10;

	$uni_group_table = table('uni_group');
	$uni_group_table->searchWithUniacidAndUid();

	$name = safe_gpc_string($_GPC['name']);
	if (!empty($name)) {
		$uni_group_table->searchWithName($name);
	}

	if (user_is_vice_founder($_W['uid'])) {
		$uni_group_table->searchWithFounderUid($_W['uid']);
	}
	$uni_group_table->searchWithPage($pageindex, $pagesize);
	$modules_group_list = $uni_group_table->getUniGroupList();
	$total = $uni_group_table->getLastQueryTotal();
	$pager = pagination($total, $pageindex, $pagesize);
	$module_support_type = module_support_type();

	if (!empty($modules_group_list)) {
		$all_module_names = array();
		foreach ($modules_group_list as $key => $value) {
			$value['modules'] = iunserializer($value['modules']);
			if (!is_array($value['modules'])) {
				$value['modules'] = array();
			}
			$modules_group_list[$key]['modules'] = $value['modules'];

			foreach ($value['modules'] as $type => $modulenames) {
				if (empty($modulenames) || !is_array($modulenames)) {
					$modules_group_list[$key][$type . '_num'] = 0;
					continue;
				} else {
					$type = 'modules' == $type ? 'account' : $type;
					$modules_group_list[$key][$type . '_num'] = count($modulenames);
				}
				$all_module_names = array_merge($all_module_names, $modulenames);
			}
		}
		$all_modules = table('modules')->searchWithName(array_unique($all_module_names))->getall('name');

		foreach ($modules_group_list as &$group) {
			if (empty($group['modules'])) {
				continue;
			}
			$template_ids = iunserializer($group['templates']);

			$group['templates'] = array();
			if (is_array($template_ids)) {
				$templates = table('site_templates')->searchWithId($template_ids)->getAll();
				if (is_array($templates)) {
					foreach ($templates as $k => $temp) {
						$temp['logo'] = $_W['siteroot'] . "app/themes/{$temp['name']}/preview.jpg";
						$group['templates'][$k] = $temp;
					}
				}
			}

			$group['modules_all'] = array();
			foreach ($module_support_type as $support => $info) {
				if (MODULE_SUPPORT_SYSTEMWELCOME_NAME == $support) {
					continue;
				}
				if (MODULE_SUPPORT_ACCOUNT_NAME == $support) {
					$info['type'] = 'modules';
				}
				if (empty($group['modules'][$info['type']])) {
					continue;
				}
				foreach ($group['modules'][$info['type']] as $modulename) {
					if (empty($all_modules[$modulename])) {
						continue;
					}
					if (empty($group['modules_all'][$modulename])) {
						$all_modules[$modulename]['logo'] = tomedia($all_modules[$modulename]['logo']);
						$group['modules_all'][$modulename] = $all_modules[$modulename];
					}
					if ($all_modules[$modulename][$support] == $info['support']) {
						$support_type = 'modules' == $info['type'] ? 'account' : $info['type'];
						$group['modules_all'][$modulename]['group_support'][] = $support_type;
					}
				}
			}
		}
	}
}

if (in_array($do, array('save', 'del', 'post'))) {
	$id = intval($_GPC['id']);
	if (!empty($id) && ACCOUNT_MANAGE_NAME_VICE_FOUNDER == $_W['highest_role']) {
		$exists = table('users_founder_own_uni_groups')->getByFounderUidAndUniGroupId($_W['uid'], $id);
		if (empty($exists)) {
			itoast('无权限操作！', referer(), 'error');
		}
	}
}

if ('save' == $do) {
	$account_all_type = uni_account_type();
	$account_all_type_sign = array_keys(uni_account_type_sign());

	$modules = safe_gpc_array($_GPC['modules']);
	$package_info = array(
		'id' => $id,
		'name' => safe_gpc_string($_GPC['name']),
		'modules' => array(),
		'templates' => safe_gpc_array($_GPC['templates']),
	);
	foreach ($account_all_type_sign as $account_type) {
		if ('account' == $account_type) {
			$package_info['modules']['modules'] = empty($modules[$account_type]) ? array() : $modules[$account_type];
		} else {
			$package_info['modules'][$account_type] = empty($modules[$account_type]) ? array() : $modules[$account_type];
		}
	}
	$package_info = module_save_group_package($package_info);

	if (is_error($package_info)) {
		iajax(1, $package_info['message'], '');
	}
	iajax(0, ($id ? '更新成功' : '添加成功'), url('module/group'));
}

if ('del' == $do) {
	if (empty($id)) {
		iajax(-1, '请选择要操作的权限组');
	}
	pdo_delete('uni_group', array('id' => $id));
	pdo_delete('users_founder_own_uni_groups', array('uni_group_id' => $id));
	cache_build_uni_group();
	cache_build_account_modules();
	iajax(0, '删除成功！', referer());
}

if ('post' == $do) {
	$group_id = $id;
	if (!empty($group_id)) {
		$group = table('uni_group')->getById($group_id);
		if (!empty($group['modules'])) {
			$group['modules'] = iunserializer($group['modules']);
		}
		if (!empty($group['templates'])) {
			$group['templates'] = iunserializer($group['templates']);
		}
	}
	$module_support_type = module_support_type();
	$module_list = array(
		'modules' => array(),
		'templates' => array(),
	);

	$user_modules = user_modules($_W['uid']);
	foreach ($user_modules as $name => $module) {
		if (!empty($module['issystem'])) {
			continue;
		}
		foreach ($module_support_type as $support => $info) {
			if (MODULE_SUPPORT_SYSTEMWELCOME_NAME == $support) {
				continue;
			}
			$info['type'] = 'account' == $info['type'] ? 'modules' : $info['type'];
			if ($module[$support] == $info['support']) {
				$module_list['modules'][] = array(
					'id' => $module['mid'],
					'name' => $module['name'],
					'title' => $module['title'],
					'logo' => $module['logo'],
					'support' => $support,
					'checked' => !empty($group['modules'][$info['type']]) && in_array($module['name'], $group['modules'][$info['type']]) ? 1 : 0,
				);
			}
		}
	}

	
		if (user_is_vice_founder($_W['uid'])) {
			$template_list = user_founder_templates($_W['user']['groupid']);
		} else {
			$template_list = pdo_getall('site_templates');
		}
	
	

	foreach ($template_list as $temp) {
		$module_list['templates'][] = array(
			'id' => $temp['id'],
			'name' => $temp['name'],
			'title' => $temp['title'],
			'logo' => $_W['siteroot'] . "app/themes/{$temp['name']}/preview.jpg",
			'support' => '',
			'checked' => !empty($group['templates']) && is_array($group['templates']) && in_array($temp['id'], $group['templates']) ? 1 : 0,
		);
	}
}
template('module/group');