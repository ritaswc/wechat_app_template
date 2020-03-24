<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
function cache_build_template() {
	load()->func('file');
	rmdirs(IA_ROOT . '/data/tpl', true);
}


function cache_build_setting() {
	$setting = table('core_settings')->getall('key');
	if (is_array($setting)) {
		foreach ($setting as $k => $v) {
			$setting[$v['key']] = iunserializer($v['value']);
		}
		cache_write(cache_system_key('setting'), $setting);
	}
}


function cache_build_account_modules($uniacid = 0, $uid = 0) {
	load()->model('phoneapp');
	load()->model('miniapp');
	$uniacid = intval($uniacid);
	if (empty($uniacid)) {
				cache_clean(cache_system_key('unimodules'));
		if (!empty($uid)) {
			cache_delete(cache_system_key('user_modules', array('uid' => $uid)));
		} else {
			cache_clean(cache_system_key('user_modules'));
		}
		return true;
	} else {
		cache_delete(cache_system_key('unimodules', array('uniacid' => $uniacid)));
		if (empty($uid)) {
			$uid = table('uni_account_users')->getUidByUniacidAndRole($uniacid, 'owner');
		}
		cache_delete(cache_system_key('user_modules', array('uid' => $uid)));
	}
	$account_info = uni_fetch($uniacid);
	if (is_error($account_info)) {
		return false;
	}

		$uni_modules_new = uni_modules_by_uniacid($uniacid);
	$module_system = module_system();
	foreach ($uni_modules_new as $uni_module_new_key => $uni_module_new_val) {
		if (in_array($uni_module_new_key, $module_system)) {
			unset($uni_modules_new[$uni_module_new_key]);
		} elseif ($account_info->typeSign == 'wxapp' && $uni_module_new_val[MODULE_SUPPORT_WXAPP_NAME] != MODULE_SUPPORT_WXAPP) {
			unset($uni_modules_new[$uni_module_new_key]);
		}
	}
	$uni_modules_new = array_keys($uni_modules_new);

	if ($account_info->supportVersion) {
		$version_modules = array();
		if ($account_info['type'] == ACCOUNT_TYPE_PHONEAPP_NORMAL) {
			$version_all = phoneapp_version_all($uniacid);
		}
		if(in_array($account_info['type'], array(ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_APP_AUTH))) {
			$version_all = miniapp_version_all($uniacid);
		}
		if (!empty($version_all)) {
			foreach($version_all as $version_key => $version_val) {
				if (empty($version_val['modules'])) {
					continue;
				}
				foreach ($version_val['modules'] as $module_name => $module_info) {
					$version_modules[] = $module_name;
				}
			}
		}
		foreach ($uni_modules_new as $uni_module_key => $uni_module_val) {
			if (empty($uni_module_val)) {
				continue;
			}
			if (empty($version_modules) || !in_array($uni_module_val, $version_modules)) {
				pdo_delete('uni_modules', array('uniacid' => $uniacid, 'module_name' => $uni_module_val));
				pdo_delete('users_lastuse', array('uniacid' => $uniacid, 'modulename' => $uni_module_val));
				unset($uni_modules_new[$uni_module_key]);
			}
		}
	}

		$uni_account_modules = table('uni_modules')->getallByUniacid($uniacid);
	$uni_account_modules = array_column($uni_account_modules, 'module_name');

	$uni_modules_add = array_diff($uni_modules_new, $uni_account_modules);
	$uni_modules_delete = array_diff($uni_account_modules, $uni_modules_new);

		$users_lastuse_table = table('users_lastuse');
	$users_lastuse_table->searchWithUniacid($uniacid);
	$uni_modules_default_list = $users_lastuse_table->getall('modulename');
	$uni_modules_default_delete = array_diff(array_keys($uni_modules_default_list), $uni_modules_new);
	if (!empty($uni_modules_default_delete)) {
		foreach ($uni_modules_default_delete as $module_default_delete_name) {
			if (empty($module_default_delete_name)) {
				continue;
			}
			pdo_delete('users_lastuse', array('uniacid' => $uniacid, 'modulename' => $module_default_delete_name));
		}
	}

	if (!empty($uni_modules_add)) {
		foreach($uni_modules_add as $module_add_name){
			$account_modules_data = array('uniacid' => $uniacid, 'module_name' => $module_add_name);
			pdo_insert('uni_modules', $account_modules_data);
		}
	}

	if (!empty($uni_modules_delete)) {
		foreach ($uni_modules_delete as $mdoule_delete_name) {
			pdo_delete('uni_modules', array('uniacid' => $uniacid, 'module_name' => $mdoule_delete_name));
		}
	}

		$modules_rank_table = table('modules_rank');
	$modules_rank_list = $modules_rank_table->getModuleListByUidAndUniacid();
	$modules_rank_list = array_keys($modules_rank_list);
	$modules_rank_add = array_diff($uni_account_modules, $modules_rank_list);
	$modules_rank_delete = array_diff($modules_rank_list, $uni_account_modules);
	asort($modules_rank_add);
	asort($modules_rank_delete);

	if (!empty($modules_rank_add)) {
		foreach ($modules_rank_add as $uni_account_module_key => $uni_account_module_name) {
			$modules_rank_data = array('uid' => $uid, 'uniacid' => $uniacid, 'module_name' => $uni_account_module_name, 'rank' => $uni_account_module_key);
			pdo_insert('modules_rank', $modules_rank_data);
		}
	}

	if (!empty($modules_rank_delete)) {
		foreach ($modules_rank_delete as $uni_account_module_name) {
			$modules_rank_data = array('uid' => $uid, 'uniacid' => $uniacid, 'module_name' => $uni_account_module_name);
			pdo_delete('modules_rank', $modules_rank_data);
		}
	}
	return true;
}


function cache_build_account($uniacid = 0) {
	$uniacid = intval($uniacid);
	if (empty($uniacid)) {
		$uniacid_arr = table('account')->getAll();
		foreach($uniacid_arr as $account){
			cache_delete(cache_system_key('uniaccount', array('uniacid' => $account['uniacid'])));
			cache_delete(cache_system_key('defaultgroupid', array('uniacid' => $account['uniacid'])));
		}
	} else {
		cache_delete(cache_system_key('uniaccount', array('uniacid' => $uniacid)));
		cache_delete(cache_system_key('defaultgroupid', array('uniacid' => $uniacid)));
	}
	return true;
}


function cache_build_memberinfo($uid) {
	$uid = intval($uid);
	cache_delete(cache_system_key('memberinfo', array('uid' => $uid)));
	return true;
}


function cache_build_users_struct() {
	$base_fields = array(
		'uniacid' => '同一公众号id',
		'groupid' => '分组id',
		'credit1' => '积分',
		'credit2' => '余额',
		'credit3' => '预留积分类型3',
		'credit4' => '预留积分类型4',
		'credit5' => '预留积分类型5',
		'credit6' => '预留积分类型6',
		'createtime' => '加入时间',
		'mobile' => '手机号码',
		'email' => '电子邮箱',
		'realname' => '真实姓名',
		'nickname' => '昵称',
		'avatar' => '头像',
		'qq' => 'QQ号',
		'gender' => '性别',
		'birth' => '生日',
		'constellation' => '星座',
		'zodiac' => '生肖',
		'telephone' => '固定电话',
		'idcard' => '证件号码',
		'studentid' => '学号',
		'grade' => '班级',
		'address' => '地址',
		'zipcode' => '邮编',
		'nationality' => '国籍',
		'reside' => '居住地',
		'graduateschool' => '毕业学校',
		'company' => '公司',
		'education' => '学历',
		'occupation' => '职业',
		'position' => '职位',
		'revenue' => '年收入',
		'affectivestatus' => '情感状态',
		'lookingfor' => ' 交友目的',
		'bloodtype' => '血型',
		'height' => '身高',
		'weight' => '体重',
		'alipay' => '支付宝帐号',
		'msn' => 'MSN',
		'taobao' => '阿里旺旺',
		'site' => '主页',
		'bio' => '自我介绍',
		'interest' => '兴趣爱好',
		'password' => '密码',
		'pay_password' => '支付密码',
	);
	cache_write(cache_system_key('userbasefields'), $base_fields);
	$fields = table('core_profile_fields')->getall('field');
	if (!empty($fields)) {
		foreach ($fields as &$field) {
			$field = $field['title'];
		}
		$fields['uniacid'] = '同一公众号id';
		$fields['groupid'] = '分组id';
		$fields['credit1'] ='积分';
		$fields['credit2'] = '余额';
		$fields['credit3'] = '预留积分类型3';
		$fields['credit4'] = '预留积分类型4';
		$fields['credit5'] = '预留积分类型5';
		$fields['credit6'] = '预留积分类型6';
		$fields['createtime'] = '加入时间';
		$fields['password'] = '用户密码';
		$fields['pay_password'] = '支付密码';
		cache_write(cache_system_key('usersfields'), $fields);
	} else {
		cache_write(cache_system_key('usersfields'), $base_fields);
	}
}

function cache_build_frame_menu() {
	global $_W;
	load()->model('system');
	$menu_table = table('core_menu');
	$system_menu_db = $menu_table->getAllByPermissionNameNotEmpty();
	$account = pdo_get('account', array('uniacid' => $_W['uniacid']));
	$system_menu = system_menu();
	if (!empty($system_menu) && is_array($system_menu)) {
		$system_displayoder = 1;
		foreach ($system_menu as $menu_name => $menu) {
			$system_menu[$menu_name]['is_system'] = true;
			$system_menu[$menu_name]['is_display'] = !empty($system_menu_db[$menu_name]['is_display']) ? true : ((isset($system_menu[$menu_name]['is_display']) && empty($system_menu[$menu_name]['is_display']) || !empty($system_menu_db[$menu_name])) ? false : true);
			$system_menu[$menu_name]['displayorder'] = !empty($system_menu_db[$menu_name]) ? intval($system_menu_db[$menu_name]['displayorder']) : ++$system_displayoder;
			if ($_W['role'] == ACCOUNT_MANAGE_NAME_EXPIRED && $menu_name != 'store' && $menu_name != 'system') {
				$system_menu[$menu_name]['is_display'] = false;
			}
			if ($menu_name == 'appmarket') {
				$system_menu[$menu_name]['is_display'] = true;
			}
			foreach ($menu['section'] as $section_name => $section) {
				$displayorder = max(count($section['menu']), 1);

								if (empty($section['menu'])) {
					$section['menu'] = array();
				}
				$menu_table->searchWithGroupName($section_name);
				$menu_table->orderby('displayorder', 'DESC');
				$add_menu = $menu_table->getall('permission_name');
				if (!empty($add_menu)) {
					foreach ($add_menu as $permission_name => $menu) {
						$menu['icon'] = !empty($menu['icon']) ? $menu['icon'] : 'wi wi-appsetting';
						$section['menu'][$permission_name] = $menu;
					}
				}
				$section_hidden_menu_count = 0;
				foreach ($section['menu'] as $permission_name => $sub_menu) {
					$sub_menu['permission_name'] = !empty($sub_menu['permission_name']) ? $sub_menu['permission_name'] : $permission_name;
					$sub_menu_db = $system_menu_db[$sub_menu['permission_name']];
					$is_display = 1;
					if (isset($sub_menu_db['is_display']) && empty($sub_menu_db['is_display'])) {
						$is_display = 0;
					}
					if ($is_display && isset($sub_menu['is_display'])) {
						if (empty($sub_menu['is_display'])) {
							unset($system_menu[$menu_name]['section'][$section_name]['menu'][$permission_name]);
							continue;
						}
						if (is_array($sub_menu['is_display']) && !in_array($account['type'], $sub_menu['is_display'])) {
							$is_display = 0;
						}
					}
					$system_menu[$menu_name]['section'][$section_name]['menu'][$permission_name] = array(
						'is_system' => isset($sub_menu['is_system']) ? $sub_menu['is_system'] : 1,
						'permission_display' => $sub_menu['is_display'],
						'is_display' => $is_display,
						'title' => !empty($sub_menu_db['title']) ? $sub_menu_db['title'] : $sub_menu['title'],
						'url' => $sub_menu['url'],
						'permission_name' => $sub_menu['permission_name'],
						'icon' => $sub_menu['icon'],
						'displayorder' => !empty($sub_menu_db['displayorder']) ? $sub_menu_db['displayorder'] : $displayorder,
						'id' => $sub_menu['id'],
						'sub_permission' => $sub_menu['sub_permission'],
					);
					$displayorder--;
					$displayorder = max($displayorder, 0);
					if (empty($system_menu[$menu_name]['section'][$section_name]['menu'][$permission_name]['is_display'])) {
						$section_hidden_menu_count++;
					}
				}
				if (empty($section['is_display']) && $section_hidden_menu_count == count($section['menu']) && $section_name != 'platform_module') {
					$system_menu[$menu_name]['section'][$section_name]['is_display'] = 0;
				}
				$system_menu[$menu_name]['section'][$section_name]['menu'] = iarray_sort($system_menu[$menu_name]['section'][$section_name]['menu'], 'displayorder', 'desc');
			}
		}
		$add_top_nav = $menu_table->searchWithGroupName('frame')->getTopMenu();

		if (!empty($add_top_nav)) {
			foreach ($add_top_nav as $menu) {
				$system_menu[$menu['permission_name']] = $menu;
				if (!empty($menu['url'])) {
					$system_menu[$menu['permission_name']]['url'] = $menu['url'];
				}
								$menu['blank'] = true;
				$system_menu[$menu['permission_name']]['is_display'] = $menu['is_display'] == 0 ? false : true;
			}
		}
		$system_menu = iarray_sort($system_menu, 'displayorder', 'asc');
		cache_delete(cache_system_key('system_frame', array('uniacid' => $_W['uniacid'])));
		cache_write(cache_system_key('system_frame', array('uniacid' => $_W['uniacid'])), $system_menu);
		return $system_menu;
	}
}

function cache_build_module_subscribe_type() {
	global $_W;
	$modules = table('modules')->getByHasSubscribes();
	if (empty($modules)) {
		return array();
	}
	$subscribe = array();
	foreach ($modules as $module) {
		$module['subscribes'] = iunserializer($module['subscribes']);
		if (!empty($module['subscribes'])) {
			foreach ($module['subscribes'] as $event) {
				if ($event == 'text') {
					continue;
				}
				$subscribe[$event][] = $module['name'];
			}
		}
	}

	$module_ban = $_W['setting']['module_receive_ban'];
	foreach ($subscribe as $event => $module_group) {
		if (!empty($module_group)) {
			foreach ($module_group as $index => $module) {
				if (!empty($module_ban[$module])) {
					unset($subscribe[$event][$index]);
				}
			}
		}
	}
	cache_write(cache_system_key('module_receive_enable'), $subscribe);
	return $subscribe;
}



function cache_build_cloud_ad() {
	global $_W;
	$uniacid_arr = table('account')->getAll();
	foreach($uniacid_arr as $account){
		cache_delete(cache_system_key('stat_todaylock', array('uniacid' => $account['uniacid'])));
		cache_delete(cache_system_key('cloud_ad_uniaccount', array('uniacid' => $account['uniacid'])));
		cache_delete(cache_system_key('cloud_ad_app_list', array('uniacid' => $account['uniacid'])));
	}
	cache_delete(cache_system_key('cloud_flow_master'));
	cache_delete(cache_system_key('cloud_ad_uniaccount_list'));
	cache_delete(cache_system_key('cloud_ad_tags'));
	cache_delete(cache_system_key('cloud_ad_type_list'));
	cache_delete(cache_system_key('cloud_ad_app_support_list'));
	cache_delete(cache_system_key('cloud_ad_site_finance'));
}


function cache_build_uninstalled_module() {
	$modulelist = table('modules')->getall('name');

	$module_root = IA_ROOT . '/addons/';
	$module_path_list = glob($module_root . '/*');
	if (empty($module_path_list)) {
		return true;
	}
	$module_support_type = module_support_type();

	foreach ($module_path_list as $path) {
		$modulename = pathinfo($path, PATHINFO_BASENAME);
		$module_recycle_info = table('modules_recycle')->searchWithNameType($modulename, MODULE_RECYCLE_UNINSTALL_IGNORE)->get();

		if (!empty($modulelist[$modulename])) {
			$module_cloud_upgrade = table('modules_cloud')->getByName($modulename);
			if (!empty($module_cloud_upgrade)) {
								$has_new_support = false;
				$installed_support = array();
				foreach (module_support_type() as $support => $value) {
					if (!empty($module_recycle_info) && $module_recycle_info[$support] == 1) {
						$installed_support[$support] = $value['not_support'];
					}
					if ($module_cloud_upgrade[$support] == $value['support'] && $modulelist[$modulename][$support] != $value['support']) {
						if ($has_new_support == false) {
							$has_new_support = true;
						}
					} else {
						$installed_support[$support] = $value['not_support'];
					}
				}
				if (empty($has_new_support)) {
					table('modules_cloud')->deleteByName($modulename); 				} else {
					$installed_support['install_status'] = MODULE_CLOUD_UNINSTALL;
					table('modules_cloud')->fill($installed_support)->where('id', $module_cloud_upgrade['id'])->save();
				}
			}
		}

		if (!is_dir($path) || !file_exists($path . '/manifest.xml')) {
			continue;
		}
		$manifest = ext_module_manifest($modulename);
		$module_upgrade_data = array(
			'name' => $modulename,
			'has_new_version' => 0,
			'has_new_branch' => 0,
			'install_status' => MODULE_LOCAL_UNINSTALL,
			'logo' => $manifest['application']['logo'],
			'version' => $manifest['application']['version'],
			'title' => $manifest['application']['name'],
			'title_initial' => get_first_pinyin($manifest['application']['name']),
		);

		if (!empty($manifest['platform']['supports'])) {
			foreach (array('app', 'wxapp', 'webapp', 'android', 'ios', 'system_welcome', 'xzapp', 'aliapp', 'baiduapp', 'toutiaoapp') as $support) {
				if (in_array($support, $manifest['platform']['supports'])) {
										if ($support == 'app') {
						$support = 'account';
					}
					if ($support == 'system_welcome') {
						$support = 'welcome';
					}
					if ($support == 'android' || $support == 'ios') {
						$support = 'phoneapp';
					}
					$module_upgrade_data["{$support}_support"] = MODULE_SUPPORT_ACCOUNT;
				}
			}
		}

		if (!empty($modulelist[$modulename])) {
			$new_support = module_check_notinstalled_support($modulelist[$modulename], $manifest['platform']['supports']);
			if (!empty($new_support)) {
				$module_upgrade_data = array_merge($module_upgrade_data, $new_support);
			} else {
				table('modules_cloud')->deleteByName($modulename);
				continue;
			}
		}

		if (!empty($module_recycle_info)) {
			foreach ($module_support_type as $support => $value) {
				if ($module_recycle_info[$support] == 1) {
					$module_upgrade_data[$support] = $value['not_support'];
				}
			}
		}
		$module_cloud_upgrade = table('modules_cloud')->getByName($modulename);
		if (empty($module_cloud_upgrade)) {
			table('modules_cloud')->fill($module_upgrade_data)->save();
		} else {
			table('modules_cloud')->fill($module_upgrade_data)->where('name', $modulename)->save();
		}
	}
	return true;
}


function cache_build_proxy_wechatpay_account() {
	global $_W;
	load()->model('account');
	$account_table = table('account');
	if (user_is_founder($_W['uid'], true)) {
		$uniaccounts = pdo_getall('account', array('type IN ' => array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH)));
	} else {
		$uniaccounts = $account_table->userOwnedAccount($_W['uid']);
	}
	$service = array();
	$borrow = array();
	if (!empty($uniaccounts)) {
		foreach ($uniaccounts as $uniaccount) {
			if (!in_array($uniaccount['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
				continue;
			}
			$account = uni_fetch($uniaccount['uniacid']);
			$payment = (array)$account['setting']['payment'];
			if (!empty($account['key']) && !empty($account['secret']) && in_array($account['level'], array (4)) &&
				is_array($payment) && !empty($payment) && intval($payment['wechat']['switch']) == 1) {

				if ((!is_bool ($payment['wechat']['switch']) && $payment['wechat']['switch'] != 4) || (is_bool ($payment['wechat']['switch']) && !empty($payment['wechat']['switch']))) {
					$borrow[$account['uniacid']] = $account['name'];
				}
			}
			if (!empty($payment['wechat_facilitator']['switch'])) {
				$service[$account['uniacid']] = $account['name'];
			}
		}
	}
	$cache = array(
		'service' => $service,
		'borrow' => $borrow
	);
	cache_write(cache_system_key('proxy_wechatpay_account'), $cache);
	return $cache;
}


function cache_build_module_info($module_name) {
	global $_W;
		table('modules_cloud')->deleteByName($module_name);
	cache_delete(cache_system_key('module_info', array('module_name' => $module_name)));
}


function cache_build_uni_group($group_id = 0) {
	cache_delete(cache_system_key('uni_groups', array('groupids' => $group_id)));
}


function cache_random($length = 4) {
	$cachekey = cache_system_key('random');
	$cache = cache_load($cachekey);
	if ($cache) {
		return $cache;
	}
	$result = random($length);
	cache_write($cachekey, $result, CACHE_EXPIRE_SHORT);
	return $result;
}

function cache_updatecache() {
	$account_ticket_cache = cache_read(cache_system_key('account_ticket'));
		pdo_delete('core_cache');
	cache_clean();
	cache_write(cache_system_key('account_ticket'), $account_ticket_cache);

	setting_save(array(), 'cloudip');
	cache_build_template();
	cache_build_users_struct();
	cache_build_setting();
	cache_build_module_subscribe_type();
		rmdirs(IA_ROOT . '/data/patch/upgrade/');
	rmdirs(IA_ROOT . '/data/tpl/web/');
	rmdirs(IA_ROOT . '/data/tpl/app/');
		pdo_delete('modules_cloud');
	return true;
}
