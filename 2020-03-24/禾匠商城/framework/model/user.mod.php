<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function user_register($user, $source) {
	global $_W;
	load()->model('message');
	if (empty($user) || !is_array($user)) {
		return 0;
	}
	if (isset($user['uid'])) {
		unset($user['uid']);
	}
	load()->classs('oauth2/oauth2client');
	$support_login_types = Oauth2CLient::supportThirdLoginType();
	if (!in_array($source, $support_login_types)) {
		$check_pass = safe_check_password(safe_gpc_string($user['password']));
		if (is_error($check_pass)) {
			return $check_pass;
		}
	}

	$user['salt'] = random(8);
	$user['password'] = user_hash($user['password'], $user['salt']);
	$user['joinip'] = CLIENT_IP;
	$user['joindate'] = TIMESTAMP;
	$user['lastip'] = CLIENT_IP;
	$user['lastvisit'] = TIMESTAMP;
	if (!empty($user['owner_uid'])) {
		$vice_founder_info = user_single($user['owner_uid']);
		if (empty($vice_founder_info) || !user_is_vice_founder($vice_founder_info['uid'])) {
			$user['owner_uid'] = 0;
		}
	}
	if (empty($user['status'])) {
		$user['status'] = 2;
	}
	if (empty($user['type'])) {
		$user['type'] = USER_TYPE_COMMON;
	}

	$result = pdo_insert('users', $user);
	if (!empty($result)) {
		$user['uid'] = pdo_insertid();
	}

	if (!empty($user['uid']) && !empty($user['owner_uid'])) {
		$founder_user_add = table('users_founder_own_users')->addOwnUser($user['uid'], $user['owner_uid']);
	}

	message_notice_record($_W['config']['setting']['founder'], MESSAGE_REGISTER_TYPE, array(
		'uid' => $user['uid'],
		'username' => $user['username'],
		'status' => $user['status'],
		'source' => $source,
		'type_name' => $user['type'] == USER_TYPE_COMMON ? '普通用户' : '应用操作员',
	));
	return intval($user['uid']);
}


function user_check($user) {
	if (empty($user) || !is_array($user)) {
		return false;
	}
	$where = ' WHERE 1 ';
	$params = array();
	if (!empty($user['uid'])) {
		$where .= ' AND `uid`=:uid';
		$params[':uid'] = intval($user['uid']);
	}
	if (!empty($user['username'])) {
		$where .= ' AND `username`=:username';
		$params[':username'] = $user['username'];
	}
	if (!empty($user['status'])) {
		$where .= " AND `status`=:status";
		$params[':status'] = intval($user['status']);
	}
	if (empty($params)) {
		return false;
	}
	$sql = 'SELECT `password`,`salt` FROM ' . tablename('users') . "$where LIMIT 1";
	$record = pdo_fetch($sql, $params);
	if (empty($record) || empty($record['password']) || empty($record['salt'])) {
		return false;
	}
	if (!empty($user['password'])) {
		$password = user_hash($user['password'], $record['salt']);
		return $password == $record['password'];
	}
	return true;
}


function user_is_founder($uid, $only_main_founder = false) {
	global $_W;
	$founders = explode(',', $_W['config']['setting']['founder']);
	if (in_array($uid, $founders)) {
		return true;
	}

	if (empty($only_main_founder)) {
		$founder_groupid = pdo_getcolumn('users', array('uid' => $uid), 'founder_groupid');
		if ($founder_groupid == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
			return true;
		}
	}

	return false;
}


function user_is_vice_founder($uid = 0) {
	global $_W;
	$uid = intval($uid);
	if (empty($uid)) {
		$user_info = $_W['user'];
	} else {
		$user_info = table('users')->getById($uid);
	}
	if ($user_info['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
		return true;
	}
	return false;
}


function user_delete($uid, $is_recycle = false) {
	load()->model('cache');
	if (!empty($is_recycle)) {
		pdo_update('users', array('status' => USER_STATUS_BAN) , array('uid' => $uid));
		return true;
	}

	$user_accounts = table('uni_account_users')->getOwnedAccountsByUid($uid);
	if (!empty($user_accounts)) {
		foreach ($user_accounts as $uniacid => $account) {
			cache_build_account_modules($uniacid);
		}
	}
	$user_info = table('users')->getById($uid);
	if ($user_info['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
		pdo_update('users', array('owner_uid' => ACCOUNT_NO_OWNER_UID), array('owner_uid' => $uid));
		pdo_update('users_group', array('owner_uid' => ACCOUNT_NO_OWNER_UID), array('owner_uid' => $uid));
		pdo_update('uni_group', array('owner_uid' => ACCOUNT_NO_OWNER_UID), array('owner_uid' => $uid));
				pdo_delete('users_founder_own_users', array('founder_uid' => $uid));
		pdo_delete('users_founder_own_users_groups', array('founder_uid' => $uid));
		pdo_delete('users_founder_own_uni_groups', array('founder_uid' => $uid));
		pdo_delete('users_founder_own_create_groups', array('founder_uid' => $uid));
	}
	pdo_delete('users', array('uid' => $uid));
	pdo_delete('uni_account_users', array('uid' => $uid));
	pdo_delete('users_profile', array('uid' => $uid));
	pdo_delete('users_bind', array('uid' => $uid));
	pdo_delete('users_extra_group', array('uid' => $uid));
	pdo_delete('users_extra_limit', array('uid' => $uid));
	pdo_delete('users_extra_modules', array('uid' => $uid));
	pdo_delete('users_extra_templates', array('uid' => $uid));
	pdo_delete('users_founder_own_users', array('uid' => $uid));
	return true;
}


function user_single($user_or_uid) {
	$user = $user_or_uid;
	if (empty($user)) {
		return false;
	}
	if (is_numeric($user)) {
		$user = array('uid' => $user);
	}
	if (!is_array($user)) {
		return false;
	}
	$where = ' WHERE 1 ';
	$params = array();
	if (!empty($user['uid'])) {
		$where .= ' AND u.`uid`=:uid';
		$params[':uid'] = intval($user['uid']);
	}
	if (!empty($user['username'])) {
		$where .= ' AND u.`username`=:username';
		$params[':username'] = $user['username'];

		$user_exists = user_check($user);
		$is_mobile = preg_match(REGULAR_MOBILE, $user['username']);
		if (!$user_exists && !empty($user['username']) && $is_mobile) {
			$sql = "select b.uid, u.username FROM " . tablename('users_bind') . " AS b LEFT JOIN " . tablename('users') . " AS u ON b.uid = u.uid WHERE b.bind_sign = :bind_sign";
			$bind_info = pdo_fetch($sql, array('bind_sign' => $user['username']));
			if (!is_array($bind_info) || empty($bind_info) || empty($bind_info['username'])) {
				return false;
			}
			$params[':username'] = $bind_info['username'];
		}
	}
	if (!empty($user['email'])) {
		$where .= ' AND u.`email`=:email';
		$params[':email'] = $user['email'];
	}
	if (!empty($user['status'])) {
		$where .= " AND u.`status`=:status";
		$params[':status'] = intval($user['status']);
	}
	if (empty($params)) {
		return false;
	}
	$sql = 'SELECT u.*, p.avatar FROM ' . tablename('users') . ' AS u LEFT JOIN '. tablename('users_profile') . ' AS p ON u.uid = p.uid '. $where. ' LIMIT 1';

	$record = pdo_fetch($sql, $params);
	if (empty($record)) {
		return false;
	}
	if (!empty($user['password'])) {
		$password = user_hash($user['password'], $record['salt']);
		if ($password != $record['password']) {
			return false;
		}
	}

	$record['hash'] = md5($record['password'] . $record['salt']);
		unset($record['password'], $record['salt']);
	$founder_own_user_info = table('users_founder_own_users')->getFounderByUid($user['uid']);
	if (!empty($founder_own_user_info) && !empty($founder_own_user_info['founder_uid'])) {
		$vice_founder_info = pdo_getcolumn('users', array('uid' => $founder_own_user_info['founder_uid']), 'username');
		if (!empty($vice_founder_info)) {
			$record['vice_founder_name'] = $vice_founder_info;
		} else {
			pdo_delete('users_founder_own_users', array('founder_uid' => $founder_own_user_info['founder_uid'], 'uid' => $founder_own_user_info['uid']));
		}
	}
	if($record['type'] == ACCOUNT_OPERATE_CLERK) {
		$clerk = pdo_get('activity_clerks', array('uid' => $record['uid']));
		if(!empty($clerk)) {
			$record['name'] = $clerk['name'];
			$record['clerk_id'] = $clerk['id'];
			$record['store_id'] = $clerk['storeid'];
			$record['store_name'] = pdo_fetchcolumn('SELECT business_name FROM ' . tablename('activity_stores') . ' WHERE id = :id', array(':id' => $clerk['storeid']));
			$record['clerk_type'] = '3';
			$record['uniacid'] = $clerk['uniacid'];
		}
	} else {
				$record['name'] = $record['username'];
		$record['clerk_id'] = $user['uid'];
		$record['store_id'] = 0;
		$record['clerk_type'] = '2';
	}
	$third_info = pdo_getall('users_bind', array('uid' => $record['uid']), array(), 'third_type');
	if (!empty($third_info) && is_array($third_info)) {
		$record['qq_openid'] = $third_info[USER_REGISTER_TYPE_QQ]['bind_sign'];
		$record['wechat_openid'] = $third_info[USER_REGISTER_TYPE_WECHAT]['bind_sign'];
		$record['mobile'] = $third_info[USER_REGISTER_TYPE_MOBILE]['bind_sign'];
	}
	$record['notice_setting'] = iunserializer($record['notice_setting']);
	return $record;
}


function user_update($user) {
	if (empty($user['uid']) || !is_array($user)) {
		return false;
	}
	$record = array();
	if (!empty($user['username'])) {
		$record['username'] = $user['username'];
	}
	if (!empty($user['password'])) {
		$record['password'] = user_hash($user['password'], $user['salt']);
	}
	if (!empty($user['lastvisit'])) {
		$record['lastvisit'] = (strlen($user['lastvisit']) == 10) ? $user['lastvisit'] : strtotime($user['lastvisit']);
	}
	if (!empty($user['lastip'])) {
		$record['lastip'] = $user['lastip'];
	}
	if (isset($user['joinip'])) {
		$record['joinip'] = $user['joinip'];
	}
	if (isset($user['remark'])) {
		$record['remark'] = $user['remark'];
	}
	if (isset($user['type'])) {
		$record['type'] = $user['type'];
	}
	if (isset($user['status'])) {
		$status = intval($user['status']);
		if (!in_array($status, array(1, 2))) {
			$status = 2;
		}
		$record['status'] = $status;
	}
	if (isset($user['groupid'])) {
		$record['groupid'] = $user['groupid'];
		$user_info = table('users')->getById($user['uid']);
		if ($user_info['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER || $user['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
			$group_info = user_founder_group_detail_info($user['groupid']);
		} else {
			$group_info = user_group_detail_info($user['groupid']);
		}
		$group_info['timelimit'] = intval($group_info['timelimit']);
		if (!empty($group_info)) {
			if ($group_info['timelimit'] > 0) {
				$extra_limit_table = table('users_extra_limit');
				$extraLimit = $extra_limit_table->getExtraLimitByUid($user_info['uid']);
				$time_limit = $group_info['timelimit'] + $extraLimit['timelimit'];
				$user_end_time = strtotime($time_limit . ' days', max($user_info['joindate'], $user_info['starttime']));
				if (user_is_vice_founder() && !empty($_W['user']['endtime'])) {
					$user_end_time = min($user_end_time, $_W['user']['endtime']);
				}
			} else {
				$user_end_time = USER_ENDTIME_GROUP_UNLIMIT_TYPE;
			}
			$record['endtime'] = $user_end_time;
		}
	}
	if (isset($user['founder_groupid'])) {
		$record['founder_groupid'] = intval($user['founder_groupid']);
	}
	if (isset($user['endtime'])) {
		$record['endtime'] = intval($user['endtime']);
	}
	if(isset($user['lastuniacid'])) {
		$record['lastuniacid'] = intval($user['lastuniacid']);
	}
	if(is_array($user['notice_setting'])) {
		$record['notice_setting'] = iserializer($user['notice_setting']);
	}
	if (empty($record)) {
		return false;
	}

	if (!empty($record['endtime'])) {
		$user_own_uniacids = pdo_getall('uni_account_users', array('uid' => $user['uid'], 'role' => 'owner'), array('uniacid'));

		if (!empty($user_own_uniacids)) {
			foreach ($user_own_uniacids as $uniacid_val) {
				$uniacid_account_info = uni_fetch($uniacid_val['uniacid']);
				if (!is_error($uniacid_account_info)) {
					pdo_update('account', array('endtime' => $record['endtime']), array('uniacid' => $uniacid_val['uniacid']));
				}
			}
		}
				$expire_notice = setting_load('user_expire');
		if (!empty($expire_notice['user_expire']['status'])) {
			$user_info = empty($user_info) ? table('users')->getById($user['uid']) : $user_info;
			if ($user_info['endtime'] != $record['endtime']) {
				pdo_update('users_profile', array('send_expire_status' => 0), array('uid' => intval($user_info['uid'])));
			}
		}
	}

	return pdo_update('users', $record, array('uid' => intval($user['uid'])));
}


function user_hash($passwordinput, $salt) {
	global $_W;
	$passwordinput = "{$passwordinput}-{$salt}-{$_W['config']['setting']['authkey']}";
	return sha1($passwordinput);
}


function user_password_hash($password, $uid) {
	if (empty($password) || intval($uid) <= 0) {
		return '';
	}
	$user_info = table('users')->getById($uid);
	if (empty($user_info)) {
		return '';
	}
	return md5($password . $user_info['salt']);
}


function user_password($passwordinput, $uid) {
	if (empty($passwordinput) || intval($uid) <= 0) {
		return '';
	}
	$user_info = table('users')->getById($uid);
	if (empty($user_info)) {
		return '';
	}
	return user_hash($passwordinput, $user_info['salt']);
}


function user_level() {
	static $level = array(
		'-3' => '锁定用户',
		'-2' => '禁止访问',
		'-1' => '禁止发言',
		'0' => '普通会员',
		'1' => '管理员',
	);
	return $level;
}


function user_group() {
	global $_W;
	$users_group_table = table('users_group');
	if (user_is_vice_founder()) {
		$users_group_table->getOwnUsersGroupsList($_W['uid']);
	}
	return $users_group_table->getUsersGroupList();
}


function user_founder_group() {
	$groups = pdo_getall('users_founder_group', array(), array('id', 'name', 'package', 'timelimit'), 'id', 'id ASC');
	return $groups;
}


function user_group_detail_info($groupid = 0) {
	$group_info = array();

	$groupid = is_array($groupid) ? 0 : intval($groupid);
	if(empty($groupid)) {
		return $group_info;
	}
	$group_info = pdo_get('users_group', array('id' => $groupid));
	if (empty($group_info)) {
		return $group_info;
	}

	$group_info['package'] = (array)iunserializer($group_info['package']);
	if (!empty($group_info['package']) && !in_array(-1, $group_info['package'])) {
		$group_info['package_detail'] = uni_groups($group_info['package']);

				$group_info['user_group_modules_all'] = array();
		if (!empty($group_info['package_detail'])) {
			foreach ($group_info['package_detail'] as $package_detail) {
				if (!empty($package_detail['modules_all'])) {
					foreach  ($package_detail['modules_all'] as $mdoule_key => $module_val) {
						$group_info['user_group_modules_all'][$mdoule_key]= $module_val;
					}
				}
			}
		}
	} else {
		$group_info['modules'] = empty($group_info['package']) ? '' : 'all';
		$group_info['templates'] = empty($group_info['package']) ? '' : 'all';
	}

	return $group_info;
}


function user_founder_group_detail_info($groupid = 0) {
	$group_info = array();

	$groupid = is_array($groupid) ? 0 : intval($groupid);
	if(empty($groupid)) {
		return $group_info;
	}
	$group_info = pdo_get('users_founder_group', array('id' => $groupid));
	if (empty($group_info)) {
		return $group_info;
	}

	$group_info['package'] = (array)iunserializer($group_info['package']);
	if (!empty($group_info['package'])) {
		$group_info['package_detail'] = uni_groups($group_info['package']);
	}
	return $group_info;
}


function user_account_detail_info($uid) {
	$account_lists = $app_user_info = $wxapp_user_info = $webapp_user_info = $xzapp_user_info = array();
	$uid = intval($uid);
	if (empty($uid)) {
		return $account_lists;
	}

	$account_users_info = table('account')->userOwnedAccount($uid);
	$account_type_signs = uni_account_type();
	$accounts = array();
	if (!empty($account_users_info)) {
		foreach ($account_users_info as $uniacid => $account) {
			$type_sign = $account_type_signs[$account['type']]['type_sign'];
			if (empty($type_sign)) {
				continue;
			}
			$account_info = uni_fetch($uniacid);
			$account_info['role'] = permission_account_user_role($uid, $uniacid);
			$accounts[$type_sign][$uniacid] = $account_info;
		}
	}
	return $accounts;
}


function user_modules($uid = 0) {
	global $_W;
	load()->model('module');
	if (empty($uid)) {
		$uid = $_W['uid'];
	}
	$support_type = module_support_type();
	$modules = cache_load(cache_system_key('user_modules', array('uid' => $uid)));
	if (empty($modules)) {
		$user_info = user_single(array ('uid' => $uid));
		$extra_modules = table('users_extra_modules')->getExtraModulesByUid($uid);

		$users_extra_group_table = table('users_extra_group');
		$extra_groups = $users_extra_group_table->getUniGroupsByUid($uid);

		if (empty($uid) || user_is_founder($uid, true)) {
			$module_list = table('modules')->getNonRecycleModules();
			$module_list = modules_support_all(array_keys($module_list));
		} elseif (!empty($user_info) && $user_info['type'] == ACCOUNT_OPERATE_CLERK && $user_info['founder_groupid'] != ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
			$clerk_module = pdo_fetch("SELECT p.type FROM " . tablename('users_permission') . " p LEFT JOIN " . tablename('uni_account_users') . " u ON p.uid = u.uid AND p.uniacid = u.uniacid WHERE u.role = :role AND p.uid = :uid", array(':role' => ACCOUNT_MANAGE_NAME_CLERK, ':uid' => $uid));
			if (empty($clerk_module)) {
				return array();
			}
			$module_list = array($clerk_module['type'] => $clerk_module['type']);
			$module_list = modules_support_all(array_keys($module_list));
		} elseif (!empty($user_info) && empty($user_info['groupid']) && empty($extra_modules) && empty($extra_groups)) {
			$module_list = pdo_getall('modules', array('issystem' => 1), array('name'), 'name');
			$module_list = modules_support_all(array_keys($module_list));
		} else {
			if ($user_info['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
				$user_group_info = user_founder_group_detail_info($user_info['groupid']);
			} else {
				$user_group_info = user_group_detail_info($user_info['groupid']);
			}
			$packageids = $user_group_info['package'];
			if (!empty($packageids) && in_array('-1', $packageids)) {
				$module_list = table('modules')->getNonRecycleModules();
				$module_list = modules_support_all(array_keys($module_list));
			} else {
								$module_list = array();	
				$package_group = (array) pdo_getall('uni_group', array('id' => $packageids));					$uni_group_add = pdo_get('uni_group', array('uid' => $uid));					if (!empty($uni_group_add)) {
					$package_group[] = $uni_group_add;
				}

				$users_extra_group_table = table('users_extra_group');
				$extra_groups = $users_extra_group_table->getUniGroupsByUid($uid);
				$extra_uni_groups = pdo_getall('uni_group', array('id' => array_keys($extra_groups)));
				$package_group = array_merge($package_group, $extra_uni_groups);
				if (!empty($package_group)) {
					foreach ($package_group as $row) {
						$row['modules'] = iunserializer($row['modules']);
						if (empty($row) || empty($row['modules'])) {
							continue;
						}
						foreach ($row['modules'] as $type => $modulenames) {
							if (!is_array($modulenames) || empty($modulenames)) {
								continue;
							}
							foreach ($modulenames as $name) {
								switch ($type) {
									case 'modules':
										$module_list[$name][] = MODULE_SUPPORT_ACCOUNT_NAME;
										break;
									case 'account':
										$module_list[$name][] = MODULE_SUPPORT_ACCOUNT_NAME;
										break;
									case 'wxapp':
										$module_list[$name][] = MODULE_SUPPORT_WXAPP_NAME;
										break;
									case 'webapp':
										$module_list[$name][] = MODULE_SUPPORT_WEBAPP_NAME;
										break;
									case 'xzapp':
										$module_list[$name][] = MODULE_SUPPORT_XZAPP_NAME;
										break;
									case 'phoneapp':
										$module_list[$name][] = MODULE_SUPPORT_PHONEAPP_NAME;
										break;
									case 'aliapp':
										$module_list[$name][] = MODULE_SUPPORT_ALIAPP_NAME;
										break;
									case 'baiduapp':
										$module_list[$name][] = MODULE_SUPPORT_BAIDUAPP_NAME;
										break;
									case 'toutiaoapp':
										$module_list[$name][] = MODULE_SUPPORT_TOUTIAOAPP_NAME;
										break;
								}
							}
						}
					}
				}
			}
		}

		if (!empty($extra_modules)) {
			foreach ($extra_modules as $extra_module_key => $extra_module_val) {
				if (!empty($module_list[$extra_module_val['module_name']]) && $module_list[$extra_module_val['module_name']] == 'all') {
					continue;
				}
				$module_list[$extra_module_val['module_name']][] = $extra_module_val['support'];
			}
		}

				$modules = array();
		if (!empty($module_list)) {
			$have_plugin_module = array();
			$plugin_list = pdo_getall('modules_plugin', array('name' => array_keys($module_list)), array());
			if (!empty($plugin_list)) {
				foreach ($plugin_list as $plugin) {
					$have_plugin_module[$plugin['main_module']][$plugin['name']] = $module_list[$plugin['name']];
					unset($module_list[$plugin['name']]);
				}
			}
			if (!empty($module_list)) {
				foreach ($module_list as $module => $support) {
					$modules[$module] = $support;
					if (!empty($have_plugin_module[$module])) {
						foreach ($have_plugin_module[$module] as $plugin => $plugin_support) {
							$modules[$plugin] = $plugin_support;
						}
					}
				}
			}
		}

		cache_write(cache_system_key('user_modules', array('uid' => $uid)), $modules);
	}

	$module_list = array();
	if (!empty($modules)) {
		$modulenames = array_keys($modules);
		$all_modules = table('modules')->searchWithName($modulenames)->getAll('name');
		$plugin_data = table('modules_plugin')->getAllByNameOrMainModule($modulenames);
		$all_recycle_info = table('modules_recycle')->searchWithNameType($modulenames, MODULE_RECYCLE_INSTALL_DISABLED)->getall('name');

		foreach ($all_modules as $k => $value) {
			$all_modules[$k]['logo'] = tomedia($all_modules[$k]['logo']);
			$all_modules[$k]['subscribes'] = (array)iunserializer($all_modules[$k]['subscribes']);
			$all_modules[$k]['handles'] = (array)iunserializer($all_modules[$k]['handles']);
			$all_modules[$k]['isdisplay'] = 1;
			$all_modules[$k]['main_module'] = '';
			$all_modules[$k]['plugin_list'] = array();
		}
		foreach ($plugin_data as $value) {
			$all_modules[$value['main_module']]['plugin_list'][] = $value['name'];
			$all_modules[$value['name']]['main_module'] = $value['main_module'];
			$all_modules[$value['name']]['main_module_logo'] = $all_modules[$value['main_module']]['logo'];
			$all_modules[$value['name']]['main_module_title'] = $all_modules[$value['main_module']]['title'];
		}
		$is_main_founder = user_is_founder($_W['uid'], true);

		foreach ($modules as $modulename => $support) {
			if (empty($all_modules[$modulename])) {
				continue;
			}
			$module_info = $all_modules[$modulename];
			foreach ($support_type as $support_name => $value) {
								if (!empty($all_recycle_info[$modulename])) {
					if ($all_recycle_info[$modulename][$support_name] > 0 && $module_info[$support_name] == $value['support']) {
						$module_info[$support_name] = $value['not_support'];
					}
				}
								if ($support !== 'all' && !empty($support)) {
					if ($module_info[$support_name] == $value['support'] && !in_array($support_name, $support)) {
						$module_info[$support_name] = $value['not_support'];
					}
				}
			}

			$is_continue = true;
			foreach ($support_type as $support_name => $value) {
								if (!$is_main_founder && $support_name == MODULE_SUPPORT_SYSTEMWELCOME_NAME) {
					continue;
				}
				if ($module_info[$support_name] == $value['support']) {
					$is_continue = false;
				}
			}
			if ($is_continue) {
				continue;
			}
			$module_list[$modulename] = $module_info;
		}
	}
	return $module_list;
}

function modules_support_all($modulenames) {
	if (empty($modulenames)) {
		return array();
	}
	$data = array();
	foreach ($modulenames as $name) {
		$data[$name] = 'all';
	}
	return $data;
}


function user_login_forward($forward = '') {
	global $_W;
	load()->model('module');
	$login_forward = trim($forward);

	if (!empty($forward)) {
		return $login_forward;
	}

	if (user_is_founder($_W['uid'], true)) {
		return url('home/welcome/system', array('page' => 'home'));
	} else {
		$user_end_time = user_end_time($_W['uid']);
		if (!empty($user_end_time) && strtotime($user_end_time) < TIMESTAMP) {
			return url('user/profile');
		}
	}

	$login_forward = user_after_login_link();
	return $login_forward;
}

function user_invite_register_url($uid = 0) {
	global $_W;
	if (empty($uid)) {
		$uid = $_W['uid'];
	}
	return $_W['siteroot'] . 'web/index.php?c=user&a=register&owner_uid=' . $uid;
}


function user_save_create_group($account_group_info) {
	global $_W;
	$account_group_table = table('users_create_group');

	$group_name = trim($account_group_info['group_name']);
	$id = $account_group_info['id'];

	if (empty($group_name)) {
		return error(-1, '账户权限组不能为空');
	}

	$account_group_table->searchWithGroupName($group_name);

	if (!empty($id)) {
		$account_group_table->searchWithoutId($id);
	}

	$account_group_exist = $account_group_table->getCreateGroupInfo();

	if (!empty($account_group_exist)) {
		return error(-1, '账户权限组已经存在！');
	}

	if (user_is_vice_founder()) {
		$premission_check_result = permission_check_vice_founder_limit($account_group_info);
		if (is_error($premission_check_result)) {
			return $premission_check_result;
		}
	}

	if (empty($id)) {
		 pdo_insert('users_create_group', $account_group_info);
		 $create_group_id = pdo_insertid();
		 if (user_is_vice_founder()) {
			$own_create_group_table = table('users_founder_own_create_groups');
			$own_create_group_table->addOwnCreateGroup($_W['uid'], $create_group_id);
		 }
	} else {
		 pdo_update('users_create_group', $account_group_info, array('id' => $account_group_info['id']));
	}
	return error(0, '添加成功!');
}


function user_save_group($group_info) {
	global $_W;
	$group_table = table('users_group');
	$name = trim($group_info['name']);
	if (empty($name)) {
		return error(-1, '用户权限组名不能为空');
	}

	$group_table->searchWithName($name);
	if (!empty($group_info['id'])) {
		$group_table->searchWithNoId($group_info['id']);
	}
	$name_exist = $group_table->getUsersGroupList();

	if (!empty($name_exist)) {
		return error(-1, '用户权限组名已存在！');
	}
	if (user_is_vice_founder()) {
		$permission_check_result = permission_check_vice_founder_limit($group_info);
		if (is_error($permission_check_result)) {
			return $permission_check_result;
		}
	}

	if (!empty($group_info['package'])) {
		foreach ($group_info['package'] as $value) {
			$package[] = intval($value);
		}
	}
	$group_info['package'] = iserializer($package);
	if (empty($group_info['id'])) {
		pdo_insert('users_group', $group_info);

		$users_group_id = pdo_insertid();
		if (user_is_vice_founder()) {
			$table = table('users_founder_own_users_groups');
			$table->addOwnUsersGroup($_W['uid'], $users_group_id);
		}
	} else {
		$old_group = $group_table->getById($group_info['id']);
		if (empty($old_group)) {
			return error(-1, '参数有误');
		}
		$result = pdo_update('users_group', $group_info, array('id' => $group_info['id']));
				if (!empty($result) && $old_group['timelimit'] != $group_info['timelimit']) {
			$all_group_users = table('users')
				->where('founder_groupid' , ACCOUNT_MANAGE_GROUP_GENERAL)
				->where('groupid' , $old_group['id'])
				->getall();
			if (!empty($all_group_users)) {
				foreach ($all_group_users as $user) {
					if ($group_info['timelimit'] > 0) {
						$endtime = strtotime($group_info['timelimit'] . ' days', max($user['joindate'], $user['starttime']));
						if (user_is_vice_founder() && !empty($_W['user']['endtime'])) {
							$endtime = min($endtime, $_W['user']['endtime']);
						}
					} else {
						$endtime = 0;
					}
					user_update(array('uid' => $user['uid'], 'endtime' => $endtime));
				}
			}
		}
	}

	return error(0, '添加成功');
}


function user_save_founder_group($group_info) {
	$name = trim($group_info['name']);
	if (empty($name)) {
		return error(-1, '用户权限组名不能为空');
	}

	if (!empty($group_info['id'])) {
		$name_exist = pdo_get('users_founder_group', array('id <>' => $group_info['id'], 'name' => $name));
	} else {
		$name_exist = pdo_get('users_founder_group', array('name' => $name));
	}

	if (!empty($name_exist)) {
		return error(-1, '用户权限组名已存在！');
	}

	if (!empty($group_info['package'])) {
		foreach ($group_info['package'] as $value) {
			$package[] = intval($value);
		}
	}
	$group_info['package'] = iserializer($package);

	if (empty($group_info['id'])) {
		pdo_insert('users_founder_group', $group_info);
	} else {
		$old_group = table('users_founder_group')->getById($group_info['id']);
		if (empty($old_group)) {
			return error(-1, '参数有误');
		}
		$result = pdo_update('users_founder_group', $group_info, array('id' => $group_info['id']));
				if (!empty($result) && $old_group['timelimit'] != $group_info['timelimit']) {
			$all_group_users = table('users')
				->where('founder_groupid' , ACCOUNT_MANAGE_GROUP_VICE_FOUNDER)
				->where('groupid' , $old_group['id'])
				->getall();
			if (!empty($all_group_users)) {
				foreach ($all_group_users as $user) {
					if ($group_info['timelimit'] > 0) {
						$endtime = strtotime($group_info['timelimit'] . ' days', max($user['joindate'], $user['starttime']));
					} else {
						$endtime = 0;
					}
					user_update(array('uid' => $user['uid'], 'endtime' => $endtime));
				}
			}
		}
	}

	return error(0, '添加成功');
}


function user_group_format($lists) {
	if (empty($lists)) {
		return $lists;
	}
	$all_package = array();
	foreach ($lists as $key => $group) {
		if (empty($group['package'])) {
			continue;
		}
		$package = iunserializer($group['package']);
		if (!is_array($package)) {
			continue;
		}
		$all_package = array_merge($all_package, $package);
	}
	$group_package = uni_groups($all_package);

	foreach ($lists as $key => $group) {
		$package = iunserializer($group['package']);
		$lists[$key]['package'] = $package;
		$group['package'] = array();
		if (is_array($package)) {
			foreach ($package as $packageid) {
				$group['package'][$packageid] = $group_package[$packageid];
			}
		}
		if (empty($package)) {
			$lists[$key]['module_nums'] = 0;
			$lists[$key]['wxapp_nums'] = 0;
			$lists[$key]['webapp_nums'] = 0;
			$lists[$key]['phoneapp_nums'] = 0;
			$lists[$key]['xzapp_nums'] = 0;
			continue;
		}
		if (is_array($package) && in_array(-1, $package)) {
			$lists[$key]['module_nums'] = -1;
			$lists[$key]['wxapp_nums'] = -1;
			$lists[$key]['webapp_nums'] = -1;
			$lists[$key]['phoneapp_nums'] = -1;
			$lists[$key]['xzapp_nums'] = -1;
			continue;
		}
		$names = array();
		$modules = array(
			'modules' => array(),
			'wxapp' => array(),
			'webapp' => array(),
			'phoneapp' => array(),
			'xzapp' => array()
		);
		if (!empty($group['package'])) {
			foreach ($group['package'] as $package) {
				$names[] = $package['name'];
				$package['modules'] = !empty($package['modules']) && is_array($package['modules']) ? array_keys($package['modules']) : array();
				$package['wxapp'] = !empty($package['wxapp']) && is_array($package['wxapp']) ? array_keys($package['wxapp']) : array();
				$package['webapp'] = !empty($package['webapp']) && is_array($package['webapp']) ? array_keys($package['webapp']) : array();
				$package['phoneapp'] = !empty($package['phoneapp']) && is_array($package['phoneapp']) ? array_keys($package['phoneapp']) : array();
				$package['xzapp'] = !empty($package['xzapp']) && is_array($package['xzapp']) ? array_keys($package['xzapp']) : array();
				$modules['modules'] = array_unique(array_merge($modules['modules'], $package['modules']));
				$modules['wxapp'] = array_unique(array_merge($modules['wxapp'], $package['wxapp']));
				$modules['webapp'] = array_unique(array_merge($modules['webapp'], $package['webapp']));
				$modules['phoneapp'] = array_unique(array_merge($modules['phoneapp'], $package['phoneapp']));
				$modules['xzapp'] = array_unique(array_merge($modules['xzapp'], $package['xzapp']));
			}
			$lists[$key]['module_nums'] = count($modules['modules']);
			$lists[$key]['wxapp_nums'] = count($modules['wxapp']);
			$lists[$key]['webapp_nums'] = count($modules['webapp']);
			$lists[$key]['phoneapp_nums'] = count($modules['phoneapp']);
			$lists[$key]['xzapp_nums'] = count($modules['xzapp']);
		}
		$lists[$key]['packages'] = implode(',', $names);
	}
	return $lists;
}


function user_end_time($uid) {
	$user = table('users')->getById($uid);
	if (user_is_vice_founder($uid)) {
		$group_info = table('users_founder_group')->getById($user['groupid']);
	} else {
		$group_info = table('users_group')->getById($user['groupid']);
	}

	$extra_limit_table = table('users_extra_limit');
	$extra_limit_info = $extra_limit_table->getExtraLimitByUid($uid);
	$total_timelimit = $group_info['timelimit'] + $extra_limit_info['timelimit'];

	if ($user['endtime'] == USER_ENDTIME_GROUP_EMPTY_TYPE || $user['endtime'] == USER_ENDTIME_GROUP_UNLIMIT_TYPE) {
		$user['end'] = 0;
	} elseif ($user['endtime'] == USER_ENDTIME_GROUP_DELETE_TYPE && $total_timelimit == 0) {
		$user['end'] = date('Y-m-d', $user['joindate']);
	}  else {
		$user['end'] = date('Y-m-d', $user['endtime']);
	}
	return $user['end'];
}


function user_list_format($users, $founder_list = true) {
	if (empty($users)) {
		return array();
	}
	$groups = table('users_group')->getall('id');
	$founder_groups = table('users_founder_group')->getall('id');
	foreach ($users as &$user) {
		$user['avatar'] = !empty($user['avatar']) ? $user['avatar'] : './resource/images/nopic-user.png';
		$user['joindate'] = date('Y-m-d', $user['joindate']);
		if ($user['endtime'] == USER_ENDTIME_GROUP_EMPTY_TYPE || $user['endtime'] == USER_ENDTIME_GROUP_UNLIMIT_TYPE) {
			$user['endtime'] = '永久有效';
		} else {
			$user['endtime'] = $user['endtime'] <= TIMESTAMP ? '服务已到期' : date('Y-m-d', $user['endtime']);
		}

		$user['module_num'] =array();
		if ($user['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
			$group = $founder_groups[$user['groupid']];
		} else {
			$group = $groups[$user['groupid']];
		}
		if ($founder_list) {
						$user['account_nums'] = permission_user_account_num($user['uid']);
		}
		$user['groupname'] = $group['name'];
		unset($user);
		unset($group);
	}
	unset($groups);
	unset($founder_groups);
	return $users;
}

function user_info_check($user) {
	if (!preg_match(REGULAR_USERNAME, $user['username'])) {
		return error(-1, '必须输入用户名，格式为 3-30 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
	}
	if (user_check(array('username' => $user['username']))) {
		return error(-2, '非常抱歉，此用户名已经被注册，你需要更换注册名称！');
	}
	if (istrlen($user['password']) < 8) {
		return error(-3, '必须输入密码，且密码长度不得低于8位。');
	} else {
		$check_pass = safe_check_password(safe_gpc_string($user['password']));
		if (is_error($check_pass)) {
			return $check_pass;
		}
	}
	if (trim($user['password']) !== trim($user['repassword'])) {
		return error(-4, '两次密码不一致！');
	}
	return error(0, '');
}


function user_info_save($user, $is_founder_group = false) {
	global $_W;
	$check_result = user_info_check($user);
	if (is_error($check_result)) {
		return $check_result;
	}
	if (intval($user['groupid'])) {
		if ($is_founder_group) {
			$group = user_founder_group_detail_info($user['groupid']);
		} else {
			$group = user_group_detail_info($user['groupid']);
		}
		if (empty($group)) {
			return error(-1, '会员组不存在');
		}
		$timelimit = intval($group['timelimit']);
	} else {
		$timelimit = 0;
	}

	$timeadd = 0;
	if ($timelimit > 0) {
		$timeadd = strtotime($timelimit . ' days');
	}
	if (user_is_vice_founder() && !empty($_W['user']['endtime'])) {
		$timeadd = !empty($timeadd) ? min($timeadd, $_W['user']['endtime']) : $_W['user']['endtime'];
	}
	if (empty($timeadd)) {
		$user['endtime'] = max(0, $user['endtime']);
	} else {
		$user['endtime'] =  empty($user['endtime']) ? $timeadd : min($timeadd, $user['endtime']);
	}
	unset($user['vice_founder_name']);
	unset($user['repassword']);
	$user_add_id = user_register($user, 'admin');
	if (empty($user_add_id)) {
		return error(-1, '增加失败，请稍候重试或联系网站管理员解决！');
	}
	return array('uid' => $user_add_id);
}


function user_detail_formate($profile) {
	if (!empty($profile)) {
		$profile['reside'] = array(
			'province' => $profile['resideprovince'],
			'city' => $profile['residecity'],
			'district' => $profile['residedist']
		);
		$profile['birth'] = array(
			'year' => $profile['birthyear'],
			'month' => $profile['birthmonth'],
			'day' => $profile['birthday'],
		);
		$profile['avatar'] = tomedia($profile['avatar']);
		$profile['resides'] = $profile['resideprovince'] . $profile['residecity'] . $profile['residedist'] ;
		$profile['births'] =($profile['birthyear'] ? $profile['birthyear'] : '--') . '年' . ($profile['birthmonth'] ? $profile['birthmonth'] : '--') . '月' . ($profile['birthday'] ? $profile['birthday'] : '--') .'日';
	}
	return $profile;
}


function user_support_urls() {
	global $_W;
	load()->classs('oauth2/oauth2client');
	$types = OAuth2Client::supportLoginType();
	$login_urls = array();
	foreach ($types as $type) {
		if (!empty($_W['setting']['thirdlogin'][$type]['authstate'])) {
			$login_urls[$type] = OAuth2Client::create($type, $_W['setting']['thirdlogin'][$type]['appid'], $_W['setting']['thirdlogin'][$type]['appsecret'])->showLoginUrl();
		}
	}
	if (empty($login_urls)) {
		$login_urls['system'] = true;
	}
	return $login_urls;
}


function user_borrow_oauth_account_list() {
	global $_W;
	$user_have_accounts = uni_user_accounts($_W['uid']);
	$oauth_accounts = array();
	$jsoauth_accounts = array();
	if(!empty($user_have_accounts)) {
		foreach($user_have_accounts as $account) {
			if(!empty($account['key']) && (!empty($account['secret']) || $account['type'] == ACCOUNT_TYPE_OFFCIAL_AUTH)) {
				if (in_array($account['level'], array(ACCOUNT_SERVICE_VERIFY))) {
					$oauth_accounts[$account['acid']] = $account['name'];
				}
				if (in_array($account['level'], array(ACCOUNT_SUBSCRIPTION_VERIFY, ACCOUNT_SERVICE_VERIFY))) {
					$jsoauth_accounts[$account['acid']] = $account['name'];
				}
			}
		}
	}
	return array(
		'oauth_accounts' => $oauth_accounts,
		'jsoauth_accounts' => $jsoauth_accounts
	);
}


function user_founder_templates($founder_groupid) {
	$group_detail_info = user_founder_group_detail_info($founder_groupid);

	if (empty($group_detail_info) || empty($group_detail_info['package'])) {
		return array();
	}

	if (in_array(-1, $group_detail_info['package'])) {
		$template_list = table('site_templates')->getAllTemplates();
		return $template_list;
	}

	$template_list = array();
	foreach ($group_detail_info['package'] as $uni_group) {
		if (!empty($group_detail_info['package_detail'][$uni_group]['templates'])) {
			$template_list = array_merge($template_list, $group_detail_info['package_detail'][$uni_group]['templates']);
		}
	}
	return $template_list;
}


function user_is_bind() {
	global $_W;
	if ($_W['isfounder']) {
		return true;
	}
	$setting_bind = empty($_W['setting']['copyright']['bind']) ? '' : $_W['setting']['copyright']['bind'];
	if (!empty($_W['user']['type']) && $_W['user']['type'] == USER_TYPE_CLERK) {
		$setting_bind = empty($_W['setting']['copyright']['clerk']['bind']) ? '' : $_W['setting']['copyright']['clerk']['bind'];
	}
	if (empty($setting_bind)) {
		return true;
	}

	load()->classs('oauth2/oauth2client');
	$type_info = OAuth2Client::supportBindTypeInfo($setting_bind);
	if (empty($type_info)) {
		return true;
	}
	return OAuth2Client::create($setting_bind)->isbind();
}


function user_check_mobile($mobile) {
	if (empty($mobile)) {
		return error(-1, '手机号不能为空');
	}
	if (!preg_match(REGULAR_MOBILE, $mobile)) {
		return error(-1, '手机号格式不正确');
	}

	$find_mobile = table('users_profile')->getByMobile($mobile);
	if (empty($find_mobile)) {
		return error(-1, '手机号不存在');
	}
	return $find_mobile;
}


function user_change_welcome_status($uid, $welcome_status) {
	if (empty($uid)) {
		return true;
	}
	$user_table = table('users');
	$user_table->fillWelcomeStatus($welcome_status)->whereUid($uid)->save();
	return true;
}


function user_after_login_link() {
	global $_W;
	$url = '';
	$type = WELCOME_DISPLAY_TYPE;
	if (!empty($_W['user']['welcome_link'])) {
		$type = $_W['user']['welcome_link'];
	}

	switch ($type) {
		case WELCOME_DISPLAY_TYPE:
			$url = './home.php';
			break;
		case PLATFORM_DISPLAY_TYPE:
		case MODULE_DISPLAY_TYPE:
		default:
			$last_operate = table('users_operate_history')->where('uid', $_W['uid'])->orderby('createtime', 'DESC')->get();
			if (USERS_OPERATE_TYPE_ACCOUNT == $last_operate['type']) {
				$url = url('account/display/platform');
			} elseif (USERS_OPERATE_TYPE_MODULE == $last_operate['type']) {
				$url = url('account/display/switch', array('module_name' => $last_operate['module_name'], 'uniacid' => $last_operate['uniacid'], 'switch_uniacid' => 1));
			}
			break;
	}
	if (empty($url)) {
		$url = './home.php';
	}
	return $url;
}

function user_available_extra_fields() {
	$default_field = array('realname', 'births', 'qq', 'mobile', 'address', 'resides');
	$fields = table('core_profile_fields')->getall();
	$extra_fields = array();
	if (!empty($fields) && is_array($fields)) {
		foreach ($fields as $field_info) {
			if ($field_info['available'] == 1 && $field_info['showinregister'] == 1 && !in_array($field_info['field'], $default_field)) {
				$extra_fields[] = $field_info;
			}
		}
	}
	return $extra_fields;
}

function user_lastuse_module_default_account() {
	return table('users_lastuse')->getDefaultModulesAccount();
}

function user_role_title($role = '') {
	$data = array(
		ACCOUNT_MANAGE_NAME_FOUNDER => '创始人',
		ACCOUNT_MANAGE_NAME_VICE_FOUNDER => '副创始人',
		ACCOUNT_MANAGE_NAME_OWNER => '主管理员',
		ACCOUNT_MANAGE_NAME_MANAGER => '管理员',
		ACCOUNT_MANAGE_NAME_OPERATOR => '操作员',
		ACCOUNT_MANAGE_NAME_CLERK => '店员',
	);
	if (!empty($role)) {
		return empty($data[$role]) ? '' : $data[$role];
	}
	return $data;
}


function user_save_operate_history($type, $value) {
	global $_W;
	$vaild_type = array(USERS_OPERATE_TYPE_ACCOUNT, USERS_OPERATE_TYPE_MODULE);
	if (!in_array($type, $vaild_type)) {
		return false;
	}
	$data = array('uid' => $_W['uid'], 'type' => $type);
	if (USERS_OPERATE_TYPE_ACCOUNT == $type) {
		$data['uniacid'] = $value;
	} elseif (USERS_OPERATE_TYPE_MODULE == $type) {
		$data['module_name'] = $value;
		$data['uniacid'] = $_W['uniacid'];
	}
	table('users_operate_history')->deleteByUidTypeOperate($data);
	$data['createtime'] = TIMESTAMP;
	$result = table('users_operate_history')->fill($data)->save();
	if ($result) {
		return true;
	} else {
		return false;
	}
}

function user_load_operate_history($limit_num = 40) {
	global $_W;
	$users_operate_history_table = table('users_operate_history');
	$users_operate_history_table->searchWithLimit($limit_num);
	$result = $users_operate_history_table->getALlByUid($_W['uid']);
	return $result;
}

function user_save_operate_star($type, $uniacid, $module_name) {
	global $_W;
	if (!in_array($type, array(USERS_OPERATE_TYPE_ACCOUNT, USERS_OPERATE_TYPE_MODULE)) || empty($uniacid)) {
		return error(-1, '参数不合法！');
	}
	if (USERS_OPERATE_TYPE_MODULE == $type) {
		if (!empty($module_name) && !module_exist_in_account($module_name, $uniacid)) {
			return error(-1, '平台账号无该模块权限，请更新缓存后重试！');
		}
	}
	$data = array('uid' => $_W['uid'], 'uniacid' => $uniacid, 'module_name' => $module_name, 'type' => $type);
	if (USERS_OPERATE_TYPE_ACCOUNT == $type) {
		unset($data['module_name']);
	}
	$if_exists = table('users_operate_star')->where($data)->get();
	if ($if_exists) {
		$result = table('users_operate_star')->where($data)->delete();
	} else {
		$data['createtime'] = TIMESTAMP;
		$maxrank = table('users_operate_star')->getMaxRank();
		$data['rank'] = intval($maxrank) + 1;
		$result = table('users_operate_star')->fill($data)->save();
	}
	if ($result) {
		return error(0, '');
	} else {
		return error(-1, '设置失败！');
	}
}

function user_load_operate_star($limit_num = 100) {
	global $_W;
	$users_operate_star_table = table('users_operate_star');
	$users_operate_star_table->searchWithLimit($limit_num);
	$result = $users_operate_star_table->getAllByUid($_W['uid']);
	return $result;
}