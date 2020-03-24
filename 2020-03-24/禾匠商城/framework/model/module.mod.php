<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function module_system() {
	return array(
		'basic', 'news', 'music', 'service', 'userapi', 'recharge', 'images', 'video', 'voice', 'wxcard',
		'custom', 'chats', 'paycenter', 'keyword', 'special', 'welcome', 'default', 'apply', 'reply', 'core', 'store',
	);
}


function module_types() {
	static $types = array(
		'business' => array(
			'name' => 'business',
			'title' => '主要业务',
			'desc' => ''
		),
		'customer' => array(
			'name' => 'customer',
			'title' => '客户关系',
			'desc' => ''
		),
		'activity' => array(
			'name' => 'activity',
			'title' => '营销及活动',
			'desc' => ''
		),
		'services' => array(
			'name' => 'services',
			'title' => '常用服务及工具',
			'desc' => ''
		),
		'biz' => array(
			'name' => 'biz',
			'title' => '行业解决方案',
			'desc' => ''
		),
		'enterprise' => array(
			'name' => 'enterprise',
			'title' => '企业应用',
			'desc' => ''
		),
		'h5game' => array(
			'name' => 'h5game',
			'title' => 'H5游戏',
			'desc' => ''
		),
		'other' => array(
			'name' => 'other',
			'title' => '其他',
			'desc' => ''
		)
	);
	return $types;
}

function module_support_type() {
		$module_support_type = array(
		'wxapp_support' => array(
			'type' => WXAPP_TYPE_SIGN,
			'type_name' => '微信小程序',
			'support' => MODULE_SUPPORT_WXAPP,
			'not_support' => MODULE_NONSUPPORT_WXAPP,
			'store_type' => STORE_TYPE_WXAPP_MODULE,
		),
		'account_support' => array(
			'type' => ACCOUNT_TYPE_SIGN,
			'type_name' => '公众号',
			'support' => MODULE_SUPPORT_ACCOUNT,
			'not_support' => MODULE_NONSUPPORT_ACCOUNT,
			'store_type' => STORE_TYPE_MODULE,
		),
		'welcome_support' => array(
			'type' => WELCOMESYSTEM_TYPE_SIGN,
			'type_name' => '系统首页',
			'support' => MODULE_SUPPORT_SYSTEMWELCOME,
			'not_support' => MODULE_NONSUPPORT_SYSTEMWELCOME,
		),
		'webapp_support' => array(
			'type' => WEBAPP_TYPE_SIGN,
			'type_name' => 'PC',
			'support' => MODULE_SUPPORT_WEBAPP,
			'not_support' => MODULE_NOSUPPORT_WEBAPP,
			'store_type' => STORE_TYPE_WEBAPP_MODULE,
		),
		'phoneapp_support' => array(
			'type' => PHONEAPP_TYPE_SIGN,
			'type_name' => 'APP',
			'support' => MODULE_SUPPORT_PHONEAPP,
			'not_support' => MODULE_NOSUPPORT_PHONEAPP,
			'store_type' => STORE_TYPE_PHONEAPP_MODULE,
		),
		'xzapp_support' => array(
			'type' => XZAPP_TYPE_SIGN,
			'type_name' => '熊掌号',
			'support' => MODULE_SUPPORT_XZAPP,
			'not_support' => MODULE_NOSUPPORT_XZAPP,
			'store_type' => STORE_TYPE_XZAPP_MODULE,
		),
		'aliapp_support' => array(
			'type' => ALIAPP_TYPE_SIGN,
			'type_name' => '支付宝小程序',
			'support' => MODULE_SUPPORT_ALIAPP,
			'not_support' => MODULE_NOSUPPORT_ALIAPP,
			'store_type' => STORE_TYPE_ALIAPP_MODULE,
		),
		'baiduapp_support' => array(
			'type' => BAIDUAPP_TYPE_SIGN,
			'type_name' => '百度小程序',
			'support' => MODULE_SUPPORT_BAIDUAPP,
			'not_support' => MODULE_NOSUPPORT_BAIDUAPP,
			'store_type' => STORE_TYPE_BAIDUAPP_MODULE,
		),
		'toutiaoapp_support' => array(
			'type' => TOUTIAOAPP_TYPE_SIGN,
			'type_name' => '头条小程序',
			'support' => MODULE_SUPPORT_TOUTIAOAPP,
			'not_support' => MODULE_NOSUPPORT_TOUTIAOAPP,
			'store_type' => STORE_TYPE_TOUTIAOAPP_MODULE,
		)
	);
	return $module_support_type;
}


function module_entries($name, $types = array(), $rid = 0, $args = null) {
	load()->func('communication');

	global $_W;
	
		$ts = array('rule', 'cover', 'menu', 'home', 'profile', 'shortcut', 'function', 'mine', 'system_welcome');
	
	
	if(empty($types)) {
		$types = $ts;
	} else {
		$types = array_intersect($types, $ts);
	}
	$bindings = pdo_getall('modules_bindings', array('module' => $name, 'entry' => $types), array(), '', 'displayorder DESC, multilevel DESC, eid ASC');
	$entries = array();
	$cache_key = cache_system_key('module_entry_call', array('module_name' => $name));
	$entry_call = cache_load($cache_key);
	if (empty($entry_call)) {
		$entry_call = array();
	}
	foreach($bindings as $bind) {
		if(!empty($bind['call'])) {
			if (empty($entry_call[$bind['entry']])) {
				$call_url = url('utility/bindcall', array('modulename' => $bind['module'], 'callname' => $bind['call'], 'args' => $args, 'uniacid' => $_W['uniacid']));
				$response = ihttp_request($call_url);
				if (is_error($response) || $response['code'] != 200) {
					$response = ihttp_request($_W['siteroot'] . 'web/' . $call_url); 					if (is_error($response) || $response['code'] != 200) {
						continue;
					}
				}
				$response = json_decode($response['content'], true);
				$ret = $response['message']['message'];
				if(is_array($ret)) {
					foreach($ret as $i => $et) {
						if (empty($et['url'])) {
							continue;
						}
						$urlinfo = url_params($et['url']);
						$et['do'] = empty($et['do']) ? $urlinfo['do'] : $et['do'];
						$et['url'] = $et['url'] . '&__title=' . urlencode($et['title']);
						$entry_call[$bind['entry']][] = array('eid' => 'user_' . $i, 'title' => $et['title'], 'do' => $et['do'], 'url' => $et['url'], 'from' => 'call', 'icon' => $et['icon'], 'displayorder' => $et['displayorder']);
					}
				}
				cache_write($cache_key, $entry_call, 300);
			}
			$entries[$bind['entry']] = $entry_call[$bind['entry']];

		} else {
			if (in_array($bind['entry'], array('cover', 'home', 'profile', 'shortcut'))) {
				$url = murl('entry', array('eid' => $bind['eid']));
			}
			if (in_array($bind['entry'], array('menu', 'system_welcome'))) {
				$url = wurl("site/entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'mine') {
				$url = $bind['url'];
			}
			if($bind['entry'] == 'rule') {
				$par = array('eid' => $bind['eid']);
				if (!empty($rid)) {
					$par['id'] = $rid;
				}
				$url = wurl("site/entry", $par);
			}

			if(empty($bind['icon'])) {
				$bind['icon'] = 'wi wi-appsetting';
			}
			if (!defined('SYSTEM_WELCOME_MODULE') && $bind['entry'] == 'system_welcome') {
				continue;
			}
			$entries[$bind['entry']][] = array(
				'eid' => $bind['eid'],
				'title' => $bind['title'],
				'do' => $bind['do'],
				'url' => !$bind['multilevel'] ? $url : '',
				'from' => 'define',
				'icon' => $bind['icon'],
				'displayorder' => $bind['displayorder'],
				'direct' => $bind['direct'],
				'multilevel' => $bind['multilevel'],
				'parent' => $bind['parent'],
			);
		}
	}
	return $entries;
}

function module_app_entries($name, $types = array(), $args = null) {
	global $_W;
	$ts = array('rule', 'cover', 'menu', 'home', 'profile', 'shortcut', 'function');
	if(empty($types)) {
		$types = $ts;
	} else {
		$types = array_intersect($types, $ts);
	}
	$bindings = pdo_getall('modules_bindings', array('module' => $name, 'entry' => $types));
	$entries = array();
	foreach($bindings as $bind) {
		if(!empty($bind['call'])) {
			$extra = array();
			$extra['Host'] = $_SERVER['HTTP_HOST'];
			load()->func('communication');
			$urlset = parse_url($_W['siteurl']);
			$urlset = pathinfo($urlset['path']);
			$response = ihttp_request($_W['sitescheme'] . $extra['Host']. $urlset['dirname'] . '/' . url('utility/bindcall', array('modulename' => $bind['module'], 'callname' => $bind['call'], 'args' => $args, 'uniacid' => $_W['uniacid'])), array('W'=>base64_encode(iserializer($_W))), $extra);
			if (is_error($response)) {
				continue;
			}
			$response = json_decode($response['content'], true);
			$ret = $response['message']['message'];
			if(is_array($ret)) {
				foreach($ret as $et) {
					$et['url'] = $et['url'] . '&__title=' . urlencode($et['title']);
					$entries[$bind['entry']][] = array('title' => $et['title'], 'url' => $et['url'], 'from' => 'call');
				}
			}
		} else {
			if($bind['entry'] == 'cover') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'home') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'profile') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			if($bind['entry'] == 'shortcut') {
				$url = murl("entry", array('eid' => $bind['eid']));
			}
			$entries[$bind['entry']][] = array('title' => $bind['title'], 'do' => $bind['do'], 'url' => $url, 'from' => 'define');
		}
	}
	return $entries;
}

function module_entry($eid) {
	$sql = "SELECT * FROM " . tablename('modules_bindings') . " WHERE `eid`=:eid";
	$pars = array();
	$pars[':eid'] = $eid;
	$entry = pdo_fetch($sql, $pars);
	if(empty($entry)) {
		return error(1, '模块菜单不存在');
	}
	$module = module_fetch($entry['module']);
	if(empty($module)) {
		return error(2, '模块不存在');
	}
	$querystring = array(
		'do' => $entry['do'],
		'm' => $entry['module'],
	);
	if (!empty($entry['state'])) {
		$querystring['state'] = $entry['state'];
	}

	$entry['url'] = murl('entry', $querystring);
	$entry['url_show'] = murl('entry', $querystring, true, true);
	return $entry;
}


function module_build_form($name, $rid, $option = array()) {
	$rid = intval($rid);
	$m = WeUtility::createModule($name);
	if(!empty($m)) {
		return $m->fieldsFormDisplay($rid, $option);
	}else {
		return null;
	}

}


function module_save_group_package($package) {
	global $_W;
	load()->model('user');
	load()->model('cache');

	if (empty($package['name'])) {
		return error(-1, '请输入套餐名');
	}

	if (!empty($package['modules'])) {
		$package['modules'] = iserializer($package['modules']);
	}

	if (!empty($package['templates'])) {
		$templates = array();
		foreach ($package['templates'] as $template) {
			$templates[] = $template['id'];
		}
		$package['templates'] = iserializer($templates);
	}

	if (!empty($package['id'])) {
		$name_exist = pdo_get('uni_group', array('uniacid' => 0, 'id <>' => $package['id'], 'name' => $package['name']));
	} else {
		$name_exist = pdo_get('uni_group', array('uniacid' => 0, 'name' => $package['name']));
	}

	if (!empty($name_exist)) {
		return error(-1, '套餐名已存在');
	}

	if (!empty($package['id'])) {
		pdo_update('uni_group', $package, array('id' => $package['id']));
		cache_build_account_modules();
	} else {
		pdo_insert('uni_group', $package);
		$uni_group_id = pdo_insertid();
		if (user_is_vice_founder()) {
			$table = table('users_founder_own_uni_groups');
			$table->addOwnUniGroup($_W['uid'], $uni_group_id);
		}
	}
	cache_build_uni_group();
	return error(0, '添加成功');
}

function module_fetch($name, $enabled = true) {
	global $_W;
	$cachekey = cache_system_key('module_info', array('module_name' => $name));
	$module = cache_load($cachekey);
	if (empty($module)) {
		$module_info = table('modules')->getByName($name);
		if (empty($module_info)) {
			return array();
		}
		if (!empty($module_info['subscribes'])) {
			$module_info['subscribes'] = (array)unserialize ($module_info['subscribes']);
		}
		if (!empty($module_info['handles'])) {
			$module_info['handles'] = (array)unserialize ($module_info['handles']);
		}
		$module_info['isdisplay'] = 1;

		$module_info['logo'] = tomedia($module_info['logo']);
		if (file_exists(IA_ROOT . '/addons/' . $module_info['name'] . '/preview-custom.jpg')) {
			$module_info['preview'] = tomedia(IA_ROOT . '/addons/' . $module_info['name'] . '/preview-custom.jpg', '', true);
		} else {
			$module_info['preview'] = tomedia(IA_ROOT . '/addons/' . $module_info['name'] . '/preview.jpg', '', true);
		}
		$module_info['main_module'] = pdo_getcolumn ('modules_plugin', array ('name' => $module_info['name']), 'main_module');
		if (!empty($module_info['main_module'])) {
			$main_module_info = module_fetch ($module_info['main_module']);
			$module_info['main_module_logo'] = $main_module_info['logo'];
			$module_info['main_module_title'] = $main_module_info['title'];
		} else {
			$module_info['plugin_list'] = pdo_getall ('modules_plugin', array ('main_module' => $module_info['name']), array (), 'name');
			if (!empty($module_info['plugin_list'])) {
				$module_info['plugin_list'] = array_keys ($module_info['plugin_list']);
			}
		}

		$module_receive_ban = (array)setting_load('module_receive_ban');
		if (is_array($module_receive_ban['module_receive_ban']) && in_array($name, $module_receive_ban['module_receive_ban'])) {
			$module_info['is_receive_ban'] = true;
		}
				$module_ban = (array)setting_load('module_ban');
		if (is_array($module_ban['module_ban']) && in_array($name, $module_ban['module_ban'])) {
			$module_info['is_ban'] = true;
		}

		$module_upgrade = (array)setting_load('module_upgrade');
		if (is_array($module_upgrade['module_upgrade']) && in_array($name, array_keys($module_upgrade['module_upgrade']))) {
			$module_info['is_upgrade'] = true;
		}

				$module_info['recycle_info'] = array();
		$recycle_info = table('modules_recycle')->getByName($name);
		if (!empty($recycle_info)) {
			$is_delete = true;
			foreach (module_support_type() as $support => $value) {
				if (!empty($recycle_info[MODULE_RECYCLE_UNINSTALL_IGNORE][$support])) {
					$module_info['recycle_info'][$support] = MODULE_RECYCLE_UNINSTALL_IGNORE; 				} else {
					$module_info['recycle_info'][$support] = empty($recycle_info[MODULE_RECYCLE_INSTALL_DISABLED][$support]) ? 0 : MODULE_RECYCLE_INSTALL_DISABLED;
				}
				if ($module_info[$support] == $value['support'] && empty($module_info['recycle_info'][$support])) {
					$is_delete = false;
				}
			}
			$module_info['is_delete'] = $is_delete; 		}

		$module = $module_info;
		cache_write($cachekey, $module_info);
	}

		if (!empty($enabled)) {
		if (!empty($module['is_delete'])) {
			return array();
		}
	}

		if (!empty($module) && !empty($_W['uniacid'])) {
		$setting_cachekey = cache_system_key('module_setting', array('module_name' => $name, 'uniacid' => $_W['uniacid']));
		$setting = cache_load($setting_cachekey);
		if (empty($setting)) {
			$setting = pdo_get('uni_account_modules', array('module' => $name, 'uniacid' => $_W['uniacid']));
			$setting = empty($setting) ? array('module' => $name) : $setting;
			cache_write($setting_cachekey, $setting);
		}
		$module['config'] = !empty($setting['settings']) ? iunserializer($setting['settings']) : array();
		$module['enabled'] = $module['issystem'] || !isset($setting['enabled']) ? 1 : $setting['enabled'];
		$module['displayorder'] = $setting['displayorder'];
		$module['shortcut'] = $setting['shortcut'];
		$module['module_shortcut'] = $setting['module_shortcut'];
	}
	return $module;
}


function module_permission_fetch($name) {
	$module = module_fetch($name);
	$data = array();
	if($module['settings']) {
		$data[] = array('title' => '参数设置', 'permission' => $name.'_settings');
	}
	if($module['isrulefields']) {
		$data[] = array('title' => '回复规则列表', 'permission' => $name.'_rule');
	}
	$entries = module_entries($name);
	if(!empty($entries['home'])) {
		$data[] = array('title' => '微站首页导航', 'permission' => $name.'_home');
	}
	if(!empty($entries['profile'])) {
		$data[] = array('title' => '个人中心导航', 'permission' => $name.'_profile');
	}
	if(!empty($entries['shortcut'])) {
		$data[] = array('title' => '快捷菜单', 'permission' => $name.'_shortcut');
	}
	if(!empty($entries['cover'])) {
		foreach($entries['cover'] as $cover) {
			$data[] = array('title' => $cover['title'], 'permission' => $name.'_cover_'.$cover['do']);
		}
	}
	if(!empty($entries['menu'])) {
		foreach($entries['menu'] as $menu) {
			if (!empty($menu['multilevel'])) {
				continue;
			}
			$data[$menu['do']] = array('title' => $menu['title'], 'permission' => $name.'_menu_'.$menu['do']);
		}
	}
	unset($entries);
	if(!empty($module['permissions'])) {
		$module['permissions'] = (array)iunserializer($module['permissions']);
		foreach ($module['permissions'] as $permission) {
			if (!empty($permission['parent']) && !empty($data[$permission['parent']])) {
				$sub_permission = array(
					'title' => $permission['title'],
					'permission' => $name . '_menu_' . $permission['parent'] . '_' . $permission['permission'],
				);
				if (empty($data[$permission['parent']]['sub_permission'])) {
					$data[$permission['parent']]['sub_permission'] = array($sub_permission);
				} else {
					array_push($data[$permission['parent']]['sub_permission'], $sub_permission);
				}
			}
			$data[] = array('title' => $permission['title'], 'permission' => $name . '_permission_' . $permission['permission']);
		}
	}
	return $data;
}



function module_get_plugin_list($module_name) {
	$module_info = module_fetch($module_name);
	if (!empty($module_info['plugin_list']) && is_array($module_info['plugin_list'])) {
		$plugin_list = array();
		foreach ($module_info['plugin_list'] as $plugin) {
			$plugin_info = module_fetch($plugin);
			if (!empty($plugin_info)) {
				$plugin_list[$plugin] = $plugin_info;
			}
		}
		return $plugin_list;
	} else {
		return array();
	}
}


function module_status($module) {
	load()->model('cloud');
	$result = array(
		'upgrade' => array(
			'has_upgrade' => false,
		),
		'ban' => false,
	);

	$module_cloud_info = table('modules_cloud')->getByName($module);
	if (!empty($module_cloud_info['has_new_version']) || !empty($module_cloud_info['has_new_branch'])) {
		$result['upgrade'] = array(
			'has_upgrade' => true,
			'name' => $module_cloud_info['title'],
			'version' => $module_cloud_info['version'],
		);
	}
	if (!empty($module_cloud_info['is_ban'])) {
		$result['ban'] = true;
	}
	return $result;
}


function module_exist_in_account($module_name, $uniacid) {
	global $_W;
	$result = false;
	$module_name = trim($module_name);
	$uniacid = intval($uniacid);
	if (empty($module_name) || empty($uniacid)) {
		return $result;
	}
	$founders = explode(',', $_W['config']['setting']['founder']);
	$owner_uid = pdo_getcolumn('uni_account_users',  array('uniacid' => $uniacid, 'role' => 'owner'), 'uid');
	if (!empty($owner_uid) && !in_array($owner_uid, $founders)) {
		if (IMS_FAMILY == 'x') {
			$account_info = uni_fetch($uniacid);
			if (in_array($account_info['type'], array(ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_APP_AUTH))) {
				$site_store_buy_goods = uni_site_store_buy_goods($uniacid, STORE_TYPE_WXAPP_MODULE);
			} else {
				$site_store_buy_goods = uni_site_store_buy_goods($uniacid);
			}
		} else {
			$site_store_buy_goods = array();
		}
		$account_table = table('account');
		$uni_modules = $account_table->accountGroupModules($uniacid);
		$user_modules = user_modules($owner_uid);
		$modules = array_merge(array_keys($user_modules), $uni_modules, $site_store_buy_goods);
		$result = in_array($module_name, $modules) ? true : false;
	} else {
		$result = true;
	}
	return $result;
}



function module_get_user_account_list($uid, $module_name) {
	$accounts_list = array();
	$uid = intval($uid);
	$module_name = trim($module_name);
	if (empty($uid) || empty($module_name)) {
		return $accounts_list;
	}
	$module_info = module_fetch($module_name);
	if (empty($module_info)) {
		return $accounts_list;
	}
	$account_users_info = table('account')->userOwnedAccount($uid);
	if (empty($account_users_info)) {
		return $accounts_list;
	}

	foreach ($account_users_info as $account) {
		if (empty($account['uniacid'])) {
			continue;
		}
		$uniacid = 0;
		if (($account['type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL || $account['type'] == ACCOUNT_TYPE_OFFCIAL_AUTH) && $module_info[MODULE_SUPPORT_ACCOUNT_NAME] == MODULE_SUPPORT_ACCOUNT) {
			$uniacid = $account['uniacid'];
		} elseif ($account['type'] == ACCOUNT_TYPE_APP_NORMAL && $module_info['wxapp_support'] == MODULE_SUPPORT_WXAPP) {
			$uniacid = $account['uniacid'];
		} elseif (($account['type'] == ACCOUNT_TYPE_XZAPP_NORMAL || $account['type'] == ACCOUNT_TYPE_XZAPP_AUTH) && $module_info[MODULE_SUPPORT_XZAPP_NAME] == MODULE_SUPPORT_XZAPP) {
			$uniacid = $account['uniacid'];
		} elseif (($account['type'] == ACCOUNT_TYPE_WEBAPP_NORMAL && $module_info[MODULE_SUPPORT_WEBAPP_NAME] == MODULE_SUPPORT_WEBAPP)) {
			$uniacid = $account['uniacid'];
		} elseif (($account['type'] == ACCOUNT_TYPE_PHONEAPP_NORMAL && $module_info[MODULE_SUPPORT_PHONEAPP_NAME] == MODULE_SUPPORT_PHONEAPP)) {
			$uniacid = $account['uniacid'];
		} elseif (($account['type'] == ACCOUNT_TYPE_ALIAPP_NORMAL && $module_info[MODULE_SUPPORT_ALIAPP_NAME] == MODULE_SUPPORT_ALIAPP)) {
			$uniacid = $account['uniacid'];
		}
		if (!empty($uniacid)) {
			if (module_exist_in_account($module_name, $uniacid)) {
				$accounts_list[$uniacid] = $account;
			}
		}
	}

	return $accounts_list;
}


function module_link_uniacid_fetch($uid, $module_name) {
	load()->model('phoneapp');
	$result = array();
	$uid = intval($uid);
	$module_name = trim($module_name);
	if (empty($uid) || empty($module_name)) {
		return $result;
	}
	$accounts_list = module_get_user_account_list($uid, $module_name);
	if (empty($accounts_list)) {
		return $result;
	}

	$accounts_link_result = array();
	foreach ($accounts_list as $key => $account_value) {
		$account_info = uni_fetch($account_value['uniacid']);
		if ($account_info->supportVersion) {
			if ($account_value['type'] == ACCOUNT_TYPE_PHONEAPP_NORMAL) {
				$account_value['versions'] = phoneapp_version_all($account_value['uniacid']);
			} else {
				$account_value['versions'] = miniapp_version_all($account_value['uniacid']);
			}
			if (empty($account_value['versions'])) {
				$accounts_link_result[$key] = $account_value;
				continue;
			}
			foreach ($account_value['versions'] as $version_key => $version_value) {
				if (empty($version_value['modules'])) {
					continue;
				}
				$version_module_names = array_column($version_value['modules'], 'name');
				if (!in_array($module_name, $version_module_names)) {
					continue;
				}
				if (empty($version_value['modules'][0]['account']) || !is_array($version_value['modules'][0]['account'])) {
					$accounts_link_result[$key] = $account_value;
					continue;
				}
				if (!empty($version_value['modules'][0]['account']['uniacid'])) {
					$accounts_link_result[$version_value['modules'][0]['account']['uniacid']][] = array(
						'uniacid' => $key,
						'version' => $version_value['version'],
						'version_id' => $version_value['id'],
						'name' => $account_value['name'],
					);
					unset($account_value['versions'][$version_key]);
				}

			}
		} elseif ($account_value['type'] == ACCOUNT_TYPE_OFFCIAL_NORMAL || $account_value['type'] == ACCOUNT_TYPE_OFFCIAL_AUTH || $account_value['type']== ACCOUNT_TYPE_XZAPP_NORMAL || $account_value['type'] == ACCOUNT_TYPE_XZAPP_AUTH) {
			if (empty($accounts_link_result[$key])) {
				$accounts_link_result[$key] = $account_value;
			} else {
				$link_wxapp = $accounts_link_result[$key];
				$accounts_link_result[$key] = $account_value;
				$accounts_link_result[$key]['link_wxapp'] = $link_wxapp;
			}
		} else {
			if (empty($accounts_link_result[$key])) {
				$accounts_link_result[$key] = $account_value;
			}
		}
	}
	if (!empty($accounts_link_result)) {
		foreach ($accounts_link_result as $link_key => $link_value) {

			if (in_array($link_value['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
				$link_value['type_name'] = ACCOUNT_TYPE_SIGN;
			} elseif (in_array($link_value['type'], array(ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_APP_AUTH))) {
				$link_value['type_name'] = WXAPP_TYPE_SIGN;
			} elseif ($link_value['type'] == ACCOUNT_TYPE_WEBAPP_NORMAL) {
				$link_value['type_name'] = WEBAPP_TYPE_SIGN;
			}elseif ($link_value['type'] == ACCOUNT_TYPE_PHONEAPP_NORMAL) {
				$link_value['type_name'] = PHONEAPP_TYPE_SIGN;
			}elseif ($link_value['type'] == ACCOUNT_TYPE_XZAPP_NORMAL) {
				$link_value['type_name'] = XZAPP_TYPE_SIGN;
			}elseif ($link_value['type'] == ACCOUNT_TYPE_ALIAPP_NORMAL) {
				$link_value['type_name'] = ALIAPP_TYPE_SIGN;
			}

			if (in_array($link_value['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH)) && !empty($link_value['link_wxapp']) && is_array($link_value['link_wxapp'])) {
				foreach ($link_value['link_wxapp'] as $value) {
					$result[] = array(
						'app_name' => $link_value['name'],
						'wxapp_name' => $value['name'] . ' ' . $value['version'],
						'uniacid' => $link_value['uniacid'],
						'version_id' => $value['version_id'],
						'type_name' => $link_value['type_name'],
						'account_name' => $link_value['name'],
						'type' => $link_value['type'],
						'logo' =>  to_global_media('headimg_' . $link_value['acid'] . '.jpg') . '?time=' . time(),
					);
				}
			} elseif ($link_value['type'] == ACCOUNT_TYPE_APP_NORMAL && !empty($link_value['versions']) && is_array($link_value['versions'])) {
				foreach ($link_value['versions'] as $value) {
					$result[] = array(
						'app_name' => '',
						'wxapp_name' => $link_value['name'] . ' ' . $value['version'],
						'uniacid' => $link_value['uniacid'],
						'version_id' => $value['id'],
						'type_name' => $link_value['type_name'],
						'account_name' => $link_value['name'],
						'type' => $link_value['type'],
						'logo' =>  to_global_media('headimg_' . $link_value['acid'] . '.jpg') . '?time=' . time(),
					);
				}
			} else {
				$result[] = array(
					'app_name' => $link_value['name'],
					'wxapp_name' => '',
					'uniacid' => $link_value['uniacid'],
					'version_id' => '',
					'type_name' => $link_value['type_name'],
					'account_name' => $link_value['name'],
					'type' => $link_value['type'],
					'logo' =>  to_global_media('headimg_' . $link_value['acid'] . '.jpg') . '?time=' . time(),
				);
			}
		}
	}

	return $result;
}


function module_clerk_info($module_name) {
	$user_permissions = array();
	$module_name = trim($module_name);
	if (empty($module_name)) {
		return $user_permissions;
	}
	$user_permissions = table('users_permission')->getClerkPermission($module_name);
	if (!empty($user_permissions)) {
		foreach ($user_permissions as $key => $value) {
			$user_permissions[$key]['user_info'] = user_single($value['uid']);
		}
	}
	return $user_permissions;
}


function module_rank_top($module_name, $uniacid) {
	global $_W;
	if (empty($module_name)) {
		return false;
	}
	$result = table('modules_rank')->setTop($module_name, $uniacid);
	return empty($result) ? true : false;
}

function module_installed_list($type = '') {
	global $_W;
	$module_list = array();
	$user_has_module = user_modules($_W['uid']);
	if (empty($user_has_module)) {
		return $module_list;
	}

	if ($type == 'all') {
		return $user_has_module;
	}

	foreach ($user_has_module as $modulename => $module) {
		if ((!empty($module['issystem']) && $module['name'] != 'we7_coupon')) {
			continue;
		}
		foreach (module_support_type() as $support_name => $support) {
			if ($module[$support_name] == $support['support'] && (empty($module['recycle_info']) || empty($module['recycle_info'][$support_name]))) {
				$module_list[$support['type']][$modulename] = $module;
			}
		}
	}

	if (!empty($type)) {
		return (array)$module_list[$type];
	} else {
		return $module_list;
	}
}


function module_uninstall_list()  {
	$uninstall_modules = table('modules_cloud')->getUninstallModule();
	$recycle = table('modules_recycle')->where('type', 2)->where('name', array_keys($uninstall_modules))->getall('name');
	if (!empty($uninstall_modules)) {
		foreach ($uninstall_modules as $name => &$module) {
			if (!empty($recycle[$name])) {
				foreach (module_support_type() as $support => $value) {
					if ($module[$support] == $value['support'] && $recycle[$name][$support] == 1) {
						$module[$support] = $value['not_support'];
					}
				}
			}
			$need_install = false;
			foreach (module_support_type() as $support => $value) {
				if ($module[$support] == MODULE_SUPPORT_ACCOUNT) {
					$need_install = true;
					$module['link'] = url('module/manage-system/install', array('module_name' => $module['name'], 'support' => $support));
					break;
				}
			}
			if (!$need_install) {
				unset($module);
			}
		}
	}
	return $uninstall_modules;
}


function module_uninstall_total($type) {
	$type_list = module_support_type();
	if (!isset($type_list["{$type}_support"]) && $type != 'all') {
		return 0;
	}
	if ($type == 'all') {
		$total = table('modules_cloud')->searchUninstallWithOutWelcome()->getcolumn('count(*)');
	} else {
		$total = table('modules_cloud')->searchUninstallSupport("{$type}_support")->getcolumn('count(*)');
	}
	return intval($total);
}

function module_upgrade_list() {
	global $_W;
	$result = array();
	$module_list = user_modules($_W['uid']);
	if (empty($module_list)) {
		return $result;
	}

	$modules_cloud_table = table('modules_cloud');
	$modules_cloud_table->orderby('buytime', 'desc');
	$modules_cloud_table->orderby('lastupdatetime', 'asc');
	$upgrade_modules = $modules_cloud_table->getUpgradeByModuleNameList(array_keys($module_list));
	if (empty($upgrade_modules)) {
		return $result;
	}

	$modules_ignore = table('modules_ignore')->where('name', array_keys($upgrade_modules))->getall('name');
	foreach ($upgrade_modules as $modulename => &$module) {
		if (!empty($modules_ignore[$modulename])) {
			if (ver_compare($modules_ignore[$modulename]['version'], $module['version']) >= 0) {
				$module['is_ignore'] = 1;
			}
		}
		$module['link'] = url('module/manage-system/module_detail', array('name' => $module['name'], 'show' => 'upgrade'));
	}
	unset($module);
	return $upgrade_modules;
}

function module_upgrade_total($type) {
	$type_list = module_support_type();

	if (!isset($type_list["{$type}_support"])) {
		return 0;
	}
	$modules = table('modules_cloud')->getUpgradeModulesBySupportType($type);
	return count($modules);
}


function module_upgrade_info($modulelist = array()) {
	load()->model('cloud');
	load()->model('extension');

	$result = array();
	$module_support_type = module_support_type();
		$manifest_cloud_list = array();

	cloud_prepare();
	$cloud_m_query_module_pageinfo = cloud_m_query(array(), 1);
	if (is_error($cloud_m_query_module_pageinfo)) {
		return $cloud_m_query_module_pageinfo;
	}
	$cloud_m_query_module = $cloud_m_query_module_pageinfo['data'];
	if ($cloud_m_query_module_pageinfo['page'] > 1) {
		for($i = 2;$i <= $cloud_m_query_module_pageinfo['page']; $i++) {
			$cloud_m_query_module_i = cloud_m_query(array(), $i);
			$cloud_m_query_module = array_merge($cloud_m_query_module, $cloud_m_query_module_i['data']);
		}
	}
	$pirate_apps = $cloud_m_query_module['pirate_apps'];
	unset($cloud_m_query_module['pirate_apps']);

	foreach ($cloud_m_query_module as $modulename => $manifest_cloud) {
		if (empty($manifest_cloud) || empty($manifest_cloud['site_branch'])) {
			continue;
		}
		$manifest = array(
			'application' => array(
				'name' => $modulename,
				'title' => $manifest_cloud['title'],
				'version' => $manifest_cloud['version'],
				'logo' => $manifest_cloud['thumb'],
				'last_upgrade_time' => $manifest_cloud['last_upgrade_time'],
			),
			'platform' => array(
				'supports' => array()
			),
		);
		foreach ($module_support_type as $support_key => $support_value) {
			if ($support_key == 'account_support' && $manifest_cloud['site_branch']['app_support'] == $support_value['support']) {
				$manifest['platform']['supports'][] = $support_value['type'];
				continue;
			}
			if ($support_key == 'phoneapp_support' && ($manifest_cloud['site_branch']['android_support'] == $support_value['support'] || $manifest_cloud['site_branch']['ios_support'] == $support_value['support'])) {
				$manifest['platform']['supports'][] = $support_value['type'];
				continue;
			}
			if ($support_key == 'welcome_support' && $manifest_cloud['site_branch']['system_welcome_support'] == $support_value['support']) {
				$manifest['platform']['supports'][] = $support_value['type'];
				continue;
			}
			if ($manifest_cloud['site_branch'][$support_key] == $support_value['support']) {
				$manifest['platform']['supports'][] = $support_value['type'];
			}
		}

		if (empty($manifest['platform']['supports'])) {
			continue;
		}
		$manifest['branches'] = $manifest_cloud['branches'];
		$manifest['site_branch'] = $manifest_cloud['site_branch'];
		$manifest['cloud_id'] = $manifest_cloud['id'];
		$manifest['buytime'] = $manifest_cloud['buytime'];
		$manifest['system_shutdown'] = $manifest_cloud['system_shutdown'];
		$manifest['system_shutdown_delay_time'] = $manifest_cloud['system_shutdown_delay_time'];
		$manifest['can_update'] = $manifest_cloud['can_update'];
		$manifest['service_expiretime'] = empty($manifest_cloud['service_expiretime']) ? 0 : $manifest_cloud['service_expiretime'];
		$manifest_cloud_list[$modulename] = $manifest;
	}

		if (empty($modulelist)) {
		$modulelist = table('modules')->searchWithType('system', '<>')->getall('name');
	}
	foreach ($modulelist as $modulename => $module) {
		if (!empty($module['issystem'])) {
			unset($modulelist[$modulename]);
			continue;
		}

		$module_upgrade_data = array(
			'name' => $modulename,
			'has_new_version' => 0,
			'has_new_branch' => 0,
		);

		if (!empty($pirate_apps) && in_array($modulename, $pirate_apps)) {
			$module_upgrade_data['is_ban'] = 1;
		}
		$manifest = ext_module_manifest($modulename);

		if (!empty($manifest)) {
			$module_upgrade_data['install_status'] = MODULE_LOCAL_INSTALL;
		} elseif ($manifest_cloud_list[$modulename]) {
			$module_upgrade_data['install_status'] = MODULE_CLOUD_INSTALL;
			$manifest = $manifest_cloud_list[$modulename];
		} else {
						$module_upgrade_data['install_status'] = MODULE_LOCAL_INSTALL;
		}

		$module_upgrade_data['logo'] = $manifest['application']['logo'];
		$module_upgrade_data['version'] = $manifest['application']['version'];
		$module_upgrade_data['title'] = $manifest['application']['title'];
		$module_upgrade_data['title_initial'] = get_first_pinyin($manifest['application']['title']);
		$module_upgrade_data['buytime'] = $manifest_cloud_list[$modulename]['buytime'];

						unset($manifest_cloud_list[$modulename]);
		$result[$modulename] = array(
			'name' => $modulename,
			'best_version' => $manifest['application']['version'],
		);
		if (version_compare($module['version'], $manifest['application']['version']) == '-1') {
				$module_upgrade_data['has_new_version'] = 1;
				$module_upgrade_data['lastupdatetime'] = TIMESTAMP;
				$result[$modulename]['new_version'] = 1;
		}

				if ($module_upgrade_data['install_status'] == MODULE_LOCAL_INSTALL && empty($module_upgrade_data['has_new_version'])) {
			continue;
		}

		if (!empty($manifest['branches'])) {
			foreach ($manifest['branches'] as &$branch) {
				if ($branch['displayorder'] > $manifest['site_branch']['displayorder'] || ($branch['displayorder'] == $manifest['site_branch']['displayorder'] && $manifest['site_branch']['id'] < intval($branch['id']))) {
					$module_upgrade_data['has_new_branch'] = 1;
					$result[$modulename]['new_branch'] = 1;
				}
			}
		}
		if (!empty($manifest['system_shutdown'])) {
			$result[$modulename]['system_shutdown'] = $manifest['system_shutdown'];
			$result[$modulename]['system_shutdown_delay_time'] = date('Y-m-d', $manifest['system_shutdown_delay_time']);
			$result[$modulename]['can_update'] = $manifest['can_update'] ? 1 : 0;
		}
		if (!empty($manifest['service_expiretime'])) {
			$result[$modulename]['service_expiretime'] = date('Y-m-d H:i:s', $manifest['service_expiretime']);
			if ($manifest['service_expiretime'] < time()) {
				$result[$modulename]['service_expire'] = 1;
			}
		}

		if (!empty($manifest['platform']['supports'])) {
			foreach ($module_support_type as $support_key => $support_value) {
				if (in_array($support_value['type'], $manifest['platform']['supports'])) {
					$module_upgrade_data[$support_key] = $support_value['support'];
				} else {
					$module_upgrade_data[$support_key] = $support_value['not_support'];
				}
			}
		}

		$module_cloud_upgrade = table('modules_cloud')->getByName($modulename);

		if (empty($module_cloud_upgrade)) {
			pdo_insert('modules_cloud', $module_upgrade_data);
		} else {
			pdo_update('modules_cloud', $module_upgrade_data, array('name' => $modulename));
		}
	}

	if (!empty($manifest_cloud_list)) {
		foreach ($manifest_cloud_list as $modulename => $manifest) {
			$module_upgrade_data = array(
				'name' => $modulename,
				'has_new_version' => 0,
				'has_new_branch' => 0,
				'install_status' => MODULE_CLOUD_UNINSTALL,
				'logo' => $manifest['application']['logo'],
				'version' => $manifest['application']['version'],
				'title' => $manifest['application']['title'],
				'title_initial' => get_first_pinyin($manifest['application']['title']),
				'lastupdatetime' => $manifest['application']['last_upgrade_time'],
				'buytime' => $manifest['buytime'],
				'cloud_id' => $manifest['cloud_id'],
			);
			if (!empty($manifest['platform']['supports'])) {
				foreach ($module_support_type as $support_key => $support_value) {
					if (in_array($support_value['type'], $manifest['platform']['supports'])) {
						$module_upgrade_data[$support_key] = $support_value['support'];
					} else {
						$module_upgrade_data[$support_key] = $support_value['not_support'];
					}
				}
			}

			$module_recycle_info = table('modules_recycle')->searchWithNameType($modulename, MODULE_RECYCLE_UNINSTALL_IGNORE)->get();
			if (!empty($module_recycle_info)) {
				foreach ($module_support_type as $support => $value) {
					if ($module_recycle_info[$support] == 1) {
						$module_upgrade_data[$support] = $value['not_support'];
					}
				}
			}

			$module_cloud_upgrade = table('modules_cloud')->getByName($modulename);
			if (empty($module_cloud_upgrade)) {
				pdo_insert('modules_cloud', $module_upgrade_data);
			} else {
				pdo_update('modules_cloud', $module_upgrade_data, array('name' => $modulename));
			}
		}
	}
	return $result;
}

function module_check_notinstalled_support($module, $manifest_support) {
	if (empty($manifest_support)) {
		return array();
	}
	$has_notinstalled_support = false;
	$notinstalled_support = array();
	$module_support_type = module_support_type();

	foreach ($manifest_support as $support) {
		if ($support == 'app') {
			$support = 'account';
		} elseif ($support == 'system_welcome') {
			$support = 'welcome';
		} elseif ($support == 'android' || $support == 'ios') {
			$support = 'phoneapp';
		}
		$support .= '_support';
		if (!in_array($support, array_keys($module_support_type))) {
			continue;
		}

		if ($module[$support] != $module_support_type[$support]['support']) {
			$has_notinstalled_support = true;
			$notinstalled_support[$support] = $module_support_type[$support]['support'];
		} else {
			$notinstalled_support[$support] = $module_support_type[$support]['not_support'];
		}
	}
	if ($has_notinstalled_support) {
		return $notinstalled_support;
	} else {
		return array();
	}
}


function module_add_to_uni_group($module, $uni_group_id, $support) {
	if (!in_array($support, array_keys(module_support_type()))) {
		return error(1, '支持类型不存在');
	}
	if (empty($module[$support]) || $module[$support] != MODULE_SUPPORT_ACCOUNT) {
		return error(1, '模块支持不存在');
	}
	$unigroup_table = table('uni_group');
	$uni_group = $unigroup_table->getById($uni_group_id);
	if (empty($uni_group)) {
		return error(1, '应用权限组不存在');
	}
	if (!empty($uni_group['modules'])) {
		$uni_group['modules'] = iunserializer($uni_group['modules']);
	}
	$update_data = $uni_group['modules'];

	$key = str_replace('_support', '', $support);
	$key = $key == 'account' ? 'modules' : $key;
	if (!in_array($module['name'], $update_data[$key])) {
		$update_data[$key][] = $module['name'];
	}
	return $unigroup_table->fill('modules', iserializer($update_data))->where('id', $uni_group_id)->save();
}


function module_recycle($modulename, $type, $support) {
	global $_W;
	$module_support_types = module_support_type();
	$module_support_type = $module_support_types[$support]['type'];
	$all_support = array_keys($module_support_types);

		if ($type == MODULE_RECYCLE_INSTALL_DISABLED) {
		$uni_modules_table = table('uni_modules');
		$uni_accounts = $uni_modules_table->where('module_name', $modulename)->getall('uniacid');
		if (!empty($uni_accounts)) {
			foreach ($uni_accounts as $uni_account_val) {
				$account_info = uni_fetch($uni_account_val['uniacid']);
				if ($account_info['type_sign'] == $module_support_type) {
					$uni_modules_table->deleteUniModules($modulename,  $uni_account_val['uniacid']);
				}
			}
		}

		$lastuse_table = table('users_lastuse');
		$lastuse_accounts = switch_getall_lastuse_by_module($modulename);
		if (!empty($lastuse_accounts)) {
			foreach ($lastuse_accounts as $lastuse_account_val) {
				$lastuse_account_info = uni_fetch($lastuse_account_val['uniacid']);
				if ($lastuse_account_info['type_sign'] == $module_support_type) {
					$lastuse_table->searchWithUid($_W['uid']);
					$lastuse_table->searchWithUniacid($lastuse_account_val['uniacid']);
					$lastuse_table->searchWithModule($modulename);
					$lastuse_table->delete();
				}
			}
		}
	}

	if (!in_array($support, $all_support)) {
		return false;
	}
	if ($type == MODULE_RECYCLE_UNINSTALL_IGNORE) {
		table('modules_cloud')->fill(array($support => 1, 'module_status' => MODULE_CLOUD_UNINSTALL_NORMAL))->where('name', $modulename)->save();
	}
	$module_recycle = table('modules_recycle');
	$record = $module_recycle->searchWithNameType($modulename, $type)->get();
	if (empty($record)) {
		return $module_recycle->fill(array('name' => $modulename, 'type' => $type, $support => 1))->save();
	} else {
		$record[$support] = 1;
		return $module_recycle->where('id', $record['id'])->fill($record)->save();
	}
}


function module_cancel_recycle($modulename, $type, $support) {
	$all_support = array_keys(module_support_type());
	if (!in_array($support, $all_support)) {
		return false;
	}
	$module_recycle = table('modules_recycle');
	$record = $module_recycle->searchWithNameType($modulename, $type)->get();
	if (empty($record)) {
		return true;
	}
	$record[$support] = 0;
	$is_update = false;
	foreach ($all_support as $s) {
		if ($record[$s] == 1) {
			$is_update = true;
		}
	}
	if ($type == MODULE_RECYCLE_UNINSTALL_IGNORE) {
		table('modules_cloud')->fill(array($support => 2, 'module_status' => MODULE_CLOUD_UNINSTALL_NORMAL))->where('name', $modulename)->save();
	}
	if ($is_update) {
		return $module_recycle->where('id', $record['id'])->fill($record)->save();
	} else {
		return $module_recycle->where('id', $record['id'])->delete();
	}
}


function module_get_direct_enter_status($module_name) {
	global $_W;
	if (empty($module_name)) {
		return STATUS_OFF;
	}
	$module_setting = table('uni_account_modules')->getByUniacidAndModule($module_name, $_W['uniacid']);
	$status = !empty($module_setting['settings']) && $module_setting['settings']['direct_enter'] == STATUS_ON ? STATUS_ON : STATUS_OFF;
	return $status;
}

function module_change_direct_enter_status($module_name) {
	global $_W;
	if (empty($module_name)) {
		return false;
	}
	$module_setting = table('uni_account_modules')->getByUniacidAndModule($module_name, $_W['uniacid']);
	$direct_enter_status = !empty($module_setting['settings']) && $module_setting['settings']['direct_enter'] == STATUS_ON ? STATUS_OFF : STATUS_ON;
	if (empty($module_setting)) {
		$data = array('direct_enter' => $direct_enter_status);
		$result = table('uni_account_modules')->fill(array('settings' => iserializer($data), 'uniacid' => $_W['uniacid'], 'module' => $module_name))->save();
	} else {
		$module_setting['settings']['direct_enter'] = $direct_enter_status;
		$data = $module_setting['settings'];
		$result = table('uni_account_modules')->fill(array('settings' => iserializer($data)))->where('module', $module_name)->where('uniacid', $_W['uniacid'])->save();
	}
	return $result ? true : false;
}

function module_delete_store_wish_goods($module_name, $support_name) {
	load()->model('store');
	$all_type = store_goods_type_info();
	foreach ($all_type as $info) {
		if ($info['group'] == 'module' && $support_name == $info['sign'] . '_support') {
			$type = $info['type'];
			break;
		}
	}
	if (!empty($type)) {
		pdo_update('site_store_goods', array('status' => 2), array('module' => $module_name, 'type' => $type));
	}
	return true;
}

function module_expire_notice() {
	$module_expire = setting_load('module_expire');
	$module_expire = !empty($module_expire['module_expire']) ? $module_expire['module_expire'] : array();
	foreach ($module_expire as $value) {
		if ($value['status'] == 1) {
			$expire_notice = $value['notice'];
			break;
		}
	}
	if (empty($expire_notice)) {
		$system_module_expire = setting_load('system_module_expire');
		$expire_notice = !empty($system_module_expire['system_module_expire']) ? $system_module_expire['system_module_expire'] : '您访问的功能模块不存在，请重新进入';
	}
	return $expire_notice;
}