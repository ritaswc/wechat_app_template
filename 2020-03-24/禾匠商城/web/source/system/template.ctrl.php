<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('extension');
load()->model('cloud');

$dos = array('installed', 'not_install', 'uninstall', 'install', 'upgrade', 'check_upgrade', 'get_upgrade_info');
$do = in_array($do, $dos) ? $do : 'installed';

if ('get_upgrade_info' == $do) {
	$template_name = $_GPC['name'];
	if (!empty($template_name)) {
		$template_info = pdo_get('site_templates', array('name' => $template_name));
		if (!empty($template_info)) {
			$cloud_t_upgrade_info = cloud_t_upgradeinfo($template_name); 			if (is_error($cloud_t_upgrade_info)) {
				iajax(1, $cloud_t_upgrade_info['message'], '');
			}
			$template_upgrade_info = array(
				'name' => $cloud_t_upgrade_info['name'],
				'title' => $template_info['title'],
				'version' => $cloud_t_upgrade_info['version'],
				'branches' => $cloud_t_upgrade_info['branches'],
				'site_branch' => $cloud_t_upgrade_info['branches'][$cloud_t_upgrade_info['version']['branch_id']],
			);
			iajax(0, $template_upgrade_info, '');
		} else {
			iajax(1, '模板不存在', '');
		}
	}
}

if ('check_upgrade' == $do) {
	$template_list = $_GPC['template'];
	if (empty($template_list) || !is_array($template_list)) {
		iajax(1, '');
	}
	$cloud_template_list = cloud_t_query();
	if (is_error($cloud_template_list)) {
		$cloud_template_list = array();
	}
	foreach ($template_list as &$template) {
		$manifest = ext_template_manifest($template['name'], false);
		if (!empty($manifest) && is_array($manifest)) {
			if ('-1' == version_compare($template['version'], $manifest['application']['version'])) {
				$template['upgrade'] = 1;
			} else {
				$template['upgrade'] = 0;
			}
			$template['from'] = 'local';
		} else {
			if (in_array($template['name'], array_keys($cloud_template_list))) {
				$template['from'] = 'cloud';
				$site_branch = $cloud_template_list[$template['name']]['branch']; 				$cloud_branch_version = $cloud_template_list[$template['name']]['branches'][$site_branch]['version']; 				$best_branch = current($cloud_template_list[$template['name']]['branches']);
				if (version_compare($template['version'], $cloud_branch_version) == -1 || ($cloud_template_list[$template['name']]['branch'] < $best_branch['id'])) {
					$template['upgrade'] = 1;
				} else {
					$template['upgrade'] = 0;
				}
			}
		}
	}
	iajax(0, $template_list, '');
}

if ('installed' == $do) {
	$pindex = max(1, $_GPC['page']);
	$pagesize = 20;
	$param = empty($_GPC['type']) ? array() : array('type' => $_GPC['type']);
	if (!empty($_GPC['keyword'])) {
		$param['title LIKE'] = '%' . trim($_GPC['keyword']) . '%';
	}

	if (!$_W['isfounder'] || user_is_vice_founder()) {
		$group_info = pdo_get('users_founder_group', array('id' => $_W['user']['groupid']));
		$group_info['package'] = unserialize($group_info['package']);
		$uni_groups = pdo_getall('uni_group', array('uniacid' => 0, 'id' => $group_info['package']), array(), '', array('id DESC'));
		$templates = array();
		foreach ($uni_groups as $key => $group) {
			$ids = unserialize($group['templates']);
			foreach ($ids as $val) {
				$templates[] = $val;
			}
		}
		$param['id'] = $templates;
	}

	$template_list = pdo_getslice('site_templates', $param, array($pindex, $pagesize), $total, array(), 'name');
	$pager = pagination($total, $pindex, $pagesize);
	$temtypes = ext_template_type();
}

if ('not_install' == $do) {
	$installed_template = pdo_getall('site_templates', array(), array(), 'name');
	$uninstall_template = array();

	$cloud_template = cloud_t_query();
	if (!is_error($cloud_template)) {
		$cloudUninstallThemes = array();
		foreach ($cloud_template as $name => $template_info) {
			if (empty($template_info) || !is_array($template_info)) {
				continue;
			}
			if (!in_array(strtolower($name), array_keys($installed_template))) {
				if (!empty($_GPC['keyword']) && !strexists($template_info['title'], trim($_GPC['keyword']))) {
					continue;
				}
				$uninstall_template[$name] = array(
					'name' => $template_info['name'],
					'title' => $template_info['title'],
					'logo' => $template_info['logo'],
					'from' => 'cloud',
				);
			}
		}
	}

	$path = IA_ROOT . '/app/themes';
	if (is_dir($path)) {
		$dir_tree = glob($path . '/*');
		if (!empty($dir_tree)) {
			foreach ($dir_tree as $modulepath) {
				$modulepath = str_replace(IA_ROOT . '/app/themes/', '', $modulepath);
				$manifest = ext_template_manifest($modulepath, false);
				if (!empty($_GPC['title']) && !strexists($manifest['title'], trim($_GPC['title']))) {
					continue;
				}
				if (!empty($manifest) && !in_array($manifest['name'], array_keys($installed_template))) {
					$uninstall_template[$manifest['name']] = $manifest;
				}
			}
		}
	}

	$total = count($uninstall_template);
	if (!empty($uninstall_template) && is_array($uninstall_template)) {
		$pindex = max(1, $_GPC['page']);
		$uninstall_template = array_slice($uninstall_template, ($pindex - 1) * 20, 20);
	}
	$pager = pagination($total, $pindex, 20);
}

if ('uninstall' == $do) {
	$template = pdo_getcolumn('site_templates', array('id' => intval($_GPC['id'])), 'name');
	if ('default' == $template) {
		itoast('默认模板不能卸载', url('system/template/not_install'), 'error');
	}
	if (pdo_delete('site_templates', array('id' => intval($_GPC['id'])))) {
		pdo_delete('site_styles', array('templateid' => intval($_GPC['id'])));
		pdo_delete('site_styles_vars', array('templateid' => intval($_GPC['id'])));
		itoast('模板移除成功, 你可以重新安装, 或者直接移除文件来安全删除！', referer(), 'success');
	} else {
		itoast('模板移除失败, 请联系模板开发者！', url('system/template/not_install'), 'error');
	}
}

if ('install' == $do) {
	if (empty($_W['isfounder'])) {
		itoast('您没有安装模块的权限', url('system/template/not_install'), 'error');
	}
	$template_name = $_GPC['templateid'];
	if (pdo_get('site_templates', array('name' => $template_name))) {
		itoast('模板已经安装或是唯一标识已存在！', url('system/template/not_install'), 'error');
	}

	$manifest = ext_template_manifest($template_name, false);
	if (!empty($manifest)) {
		$prepare_result = cloud_t_prepare($template_name);
		if (is_error($prepare_result)) {
			itoast($prepare_result['message'], url('system/template/not_install'), 'error');
		}
	}
	if (empty($manifest)) {
		$cloud_result = cloud_prepare();
		if (is_error($cloud_result)) {
			itoast($cloud_result['message'], url('cloud/profile'), 'error');
		}
		$template_info = cloud_t_info($template_name);
		if (!is_error($template_info)) {
			if (empty($_GPC['flag'])) {
				header('location: ' . url('cloud/process', array('t' => $template_name)));
				exit;
			} else {
				$packet = cloud_t_build($template_name);
				$manifest = ext_template_manifest_parse($packet['manifest']);
				$manifest['version'] = $packet['version'];
			}
		} else {
			itoast($template_info['message'], '', 'error');
		}
	}
	unset($manifest['settings']);
	$module_group = uni_groups();
	if (!$_W['ispost'] || empty($_GPC['flag'])) {
		template('system/module-group');
		exit;
	}
	$post_groups = $_GPC['group'];
	$tid = intval($_GPC['tid']);

	$template_name = $_GPC['templateid'];
	if (empty($manifest)) {
		itoast('模板安装配置文件不存在或是格式不正确！', '', 'error');
	}
	if ($manifest['name'] != $template_name) {
		itoast('安装模板与文件标识不符，请重新安装', '', 'error');
	}
	if (pdo_get('site_templates', array('name' => $manifest['name']))) {
		itoast('模板已经安装或是唯一标识已存在！', url('system/template/not_install'), 'error');
	}
	if (pdo_insert('site_templates', $manifest)) {
		$tid = pdo_insertid();
	} else {
		itoast('模板安装失败, 请联系模板开发者！', '', 'error');
	}
	if ($template_name && $post_groups) {
		if (!pdo_get('site_templates', array('id' => $tid))) {
			itoast('指定模板不存在！', '', 'error');
		}
		foreach ($post_groups as $post_group) {
			$group = pdo_get('uni_group', array('id' => $post_group));
			if (empty($group)) {
				continue;
			}
			$group['templates'] = iunserializer($group['templates']);
			if (in_array($tid, $group['templates'])) {
				continue;
			}
			$group['templates'][] = $tid;
			$group['templates'] = iserializer($group['templates']);
			pdo_update('uni_group', $group, array('id' => $post_group));
		}
	}
	itoast('模板安装成功, 请按照【公众号服务套餐】【用户组】来分配权限！', url('system/template'), 'success');
}

if ('upgrade' == $do) {
	$template_name = $_GPC['templateid'];
	$template = pdo_get('site_templates', array('name' => $template_name));
	if (empty($template)) {
		itoast('模板已经被卸载或是不存在！', url('system/template'), 'error');
	}
	if (!is_error($info)) {
		if (!empty($_GPC['flag'])) {
			$packet = cloud_t_build($template_name);
			$manifest = ext_template_manifest_parse($packet['manifest']);
		}
	}
	if (empty($manifest)) {
		itoast('模块安装配置文件不存在或是格式不正确！', '', 'error');
	}
	if (version_compare($template['version'], $packet['version']) != -1) {
		itoast('已安装的模板版本不低于要更新的版本, 操作无效.', '', 'error');
	}
	pdo_update('site_templates', array('version' => $packet['version']), array('id' => $template['id']));
	itoast('模板更新成功！', url('system/template'), 'success');
}

template('system/template');