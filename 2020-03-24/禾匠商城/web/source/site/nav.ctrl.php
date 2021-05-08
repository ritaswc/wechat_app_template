<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('home', 'profile', 'homemenu_display', 'homemenu_post', 'homemenu_del', 'homemenu_switch');
$do = in_array($do, $dos) ? $do : 'home';

$system_modules = module_system();
if (!in_array($_GPC['m'], $system_modules)) {
	permission_check_account_user('', true, 'nav');
}
$modulename = $_GPC['m'];

if ('homemenu_display' == $do) {
	$multiid = intval($_GPC['multiid']);
	$navs = table('site_nav')
		->getBySnake('*', array('uniacid' => $_W['uniacid'], 'position' => '1', 'multiid' => $multiid), array('displayorder' => 'DESC', 'id' => 'ASC'))
		->getall();
	$navigations = array();
	if (!empty($navs)) {
		foreach ($navs as $nav) {
			
			if (is_serialized($nav['css'])) {
				$nav['css'] = iunserializer($nav['css']);
				if (empty($nav['css']['icon']['icon'])) {
					$nav['css']['icon']['icon'] = 'fa fa-external-link';
				}
			}
			$navigations[] = array(
				'id' => $nav['id'],
				'module' => $nav['module'],
				'name' => $nav['name'],
				'url' => $nav['url'],
				'from' => $nav['module'] ? 'define' : 'custom',
				'status' => $nav['status'],
				'remove' => true,
				'displayorder' => $nav['displayorder'],
				'icon' => $nav['icon'],
				'css' => $nav['css'],
				'section' => $nav['section'],
				'description' => $nav['description'],
			);
		}
	}
	iajax(0, $navigations, '');
}
if ('homemenu_post' == $do) {
	$multiid = intval($_GPC['multiid']);
	$post = $_GPC['menu_info'];
	if (empty($post['name'])) {
		iajax(-1, '抱歉，请输入导航菜单的名称！', '');
	}

	if (strexists($post['url'], 'tel')) {
		$url = safe_gpc_string($post['url'], false);
	} else {
		$url = safe_gpc_url($post['url'], false);
	}

	if (is_array($post['section']) && !empty($post['section'])) {
		if (intval($post['section']['num']) > 10) {
			$section_num = 10;
		} else {
			$section_num = intval($post['section']['num']);
		}
	} else {
		$section_num = 0;
	}
	$data = array(
		'uniacid' => $_W['uniacid'],
		'multiid' => $multiid,
		'section' => $section_num,
		'name' => safe_gpc_string($post['name']),
		'description' => safe_gpc_string($post['description']),
		'displayorder' => intval($post['displayorder']),
		'url' => $url,
		'status' => intval($post['status']),
		'position' => 1,
	);
		$icontype = safe_gpc_string($post['icontype']);
	if (1 == $icontype) {
		$data['icon'] = '';
		$data['css'] = serialize(array(
				'icon' => array(
					'font-size' => intval($post['css']['icon']['width']),
					'color' => safe_gpc_string($post['css']['icon']['color']),
					'width' => intval($post['css']['icon']['width']),
					'icon' => empty($post['css']['icon']['icon']) ? 'fa fa-external-link' : safe_gpc_string($post['css']['icon']['icon']),
				),
				'name' => array(
					'color' => safe_gpc_string($post['css']['icon']['color']),
				),
			)
		);
	} else {
		$data['css'] = '';
		$data['icon'] = $post['icon'];
	}
	if (empty($post['id'])) {
		table('site_nav')
			->fill($data)
			->save();
	} else {
		table('site_nav')
			->where(array('id' => $post['id'], 'uniacid' => $_W['uniacid']))
			->fill($data)
			->save();
	}
	iajax(0, '更新成功！', '');
}

if ('homemenu_del' == $do) {
	$id = intval($_GPC['id']);
	$nav_exist = table('site_nav')
		->where(array('id' => $id, 'uniacid' => $_W['uniacid']))
		->get();
	if (empty($nav_exist)) {
				iajax(-1, '本公众号不存在该导航！', '');
	} else {
		$nav_del = table('site_nav')
			->where(array('id' => $id))
			->delete();
		if (!empty($nav_del)) {
			iajax(0, '删除成功！', '');
		} else {
						iajax(1, '删除失败！', '');
		}
	}
	exit;
}

if ('homemenu_switch' == $do) {
	$id = intval($_GPC['id']);
	$nav_exist = table('site_nav')
		->where(array('id' => $id, 'uniacid' => $_W['uniacid']))
		->get();
	if (empty($nav_exist)) {
		iajax(-1, '本公众号不存在该导航');
	} else {
		$status = 1 == $nav_exist['status'] ? 0 : 1;
		$nav_update = table('site_nav')
			->where(array('id' => $id, 'uniacid' => $_W['uniacid']))
			->fill(array('status' => $status))
			->save();
		if (!empty($nav_update)) {
			iajax(0, '更新成功！', '');
		} else {
			iajax(1, '更新失败！', '');
		}
	}
}

if ('home' == $do || 'profile' == $do) {
	$modules = uni_modules();
	$bindings = array();
	define('IN_MODULE', $modulename);
	if (!empty($modulename)) {
		$modulenames = array($modulename);
	} else {
		$modulenames = array_keys($modules);
	}

	$module = $_W['current_module'] = module_fetch($modulename);
	foreach ($modulenames as $modulename) {
		$entries = module_entries($modulename, array($do));
		if (!empty($entries[$do])) {
			$bindings[$modulename] = $entries[$do];
		}
	}
	$entries = array();
	if (!empty($bindings)) {
		foreach ($bindings as $modulename => $group) {
			foreach ($group as $bind) {
				$entries[] = array('module' => $modulename, 'from' => $bind['from'], 'title' => $bind['title'], 'url' => $bind['url'], 'icon' => $bind['icon']);
			}
		}
	}
	template('site/nav');
}