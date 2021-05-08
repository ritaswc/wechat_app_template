<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('display', 'rank', 'module_shortcut');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

$module_name = trim($_GPC['m']);
$uniacid = intval($_GPC['uniacid']);
$modulelist = uni_modules();
$module = $_W['current_module'] = $modulelist[$module_name];

if ('display' == $do) {
	$modules_plugin_rank_table = table('modules_plugin_rank');
	$modules_plugin_rank_table->searchWithUid($_W['uid']);
	$plugin_list = $modules_plugin_rank_table->getPluginRankList($module_name, $uniacid);

	if (!empty($module['plugin_list'])) {
		foreach ($module['plugin_list'] as $plugin_name) {
			$plugin_rank_exists = $modules_plugin_rank_table->getByPluginNameAndUniacid($plugin_name, $uniacid);
			if (empty($plugin_rank_exists)) {
				$modules_plugin_rank_table->fill(array('uniacid' => $uniacid, 'uid' => $_W['uid'], 'rank' => 0, 'plugin_name' => $plugin_name, 'main_module_name' => $module_name))->save();
			}
		}
	}

		$module_menu_plugin_list = table('core_menu_shortcut')->getCurrentModuleMenuPluginList($module_name);

	if (!empty($plugin_list)) {
		foreach ($plugin_list as $plugin_key => &$plugin_val) {
			if (empty($modulelist[$plugin_key])) {
				unset($plugin_list[$plugin_key]);
				continue;
			}
			if (!empty($plugin_val['uid']) && $plugin_val['uid'] != $_W['uid']) {
				unset($plugin_list[$plugin_key]);
				continue;
			}
			$plugin_val['plugin_info'] = module_fetch($plugin_val['name']);
			if (empty($plugin_val['plugin_info'])) {
				unset($plugin_list[$plugin_key]);
			}
			if (in_array($plugin_val['name'], array_keys($module_menu_plugin_list))) {
				$plugin_val['module_shortcut'] = 1;
			}
		}
	}

	template('module/plugin');
}

if ('rank' == $do) {
	$plugin_name = trim($_GPC['plugin_name']);
	$main_module_name = trim($_GPC['main_module_name']);
	$uniacid = intval($_GPC['uniacid']);
	$exist = module_fetch($plugin_name, $uniacid);
	if (empty($exist)) {
		iajax(1, '模块不存在', '');
	}
	table('modules_plugin_rank')->setTop($plugin_name, $main_module_name, $uniacid);
	itoast('更新成功！', referer(), 'success');
}

if ('module_shortcut' == $do) {
	global $_W;
	$status = intval($_GPC['module_shortcut']);
	$plugin_name = $_GPC['plugin_name'];

	$module_info = module_fetch($plugin_name);
	if (empty($module_info)) {
		itoast('模块不能被访问!', referer(), 'error');
	}
	$main_module_name = $module_info['main_module'];
	$position = 'module_' . $main_module_name . '_menu_plugin_shortcut';
	$plugin_shortcut = pdo_get('core_menu_shortcut', array('position' => $position, 'modulename' => $plugin_name, 'uniacid' => $_W['uniacid'], 'uid' => $_W['uid']));

	if (empty($plugin_shortcut)) {
		$data = array(
			'uid' => $_W['uid'],
			'uniacid' => $_W['uniacid'],
			'modulename' => $plugin_name,
			'position' => $position,
		);
		pdo_insert('core_menu_shortcut', $data);
	} else {
		pdo_delete('core_menu_shortcut', array('id' => $plugin_shortcut['id']));
	}
	cache_build_module_info($module_name);
	itoast('设置成功!', referer(), 'success');
}