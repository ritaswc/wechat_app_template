<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('miniapp');
load()->model('phoneapp');

$dos = array('rank', 'display', 'list', 'switch', 'platform', 'history', 'setting_star', 'setting_star_rank', 'list_star', 'account_num', 'welcome_link', 'account_modules');
$do = in_array($_GPC['do'], $dos) ? $do : 'display';

if ('platform' == $do) {
	if ($_W['isfounder']) {
		$url = url('account/display');
	} else {
		$url = $_W['siteroot'] . 'web/home.php';
	}
	$last_uniacid = switch_get_account_display();
	if (empty($last_uniacid)) {
		itoast('', $url, 'info');
	}
	if (!empty($last_uniacid) && $last_uniacid != $_W['uniacid']) {
		switch_save_account_display($last_uniacid);
	}
	$permission = permission_account_user_role($_W['uid'], $last_uniacid);
	if (empty($permission)) {
		itoast('', $url, 'info');
	}
	$account_info = uni_fetch($last_uniacid);

	if (ACCOUNT_TYPE_SIGN == $account_info['type_sign'] || XZAPP_TYPE_SIGN == $account_info['type_sign']) {
		$url = url('home/welcome');
	} elseif (WEBAPP_TYPE_SIGN == $account_info['type_sign']) {
		$url = url('webapp/home/display');
	} else {
		$last_version = miniapp_fetch($last_uniacid);
		if (!empty($last_version)) {
			$url = url('miniapp/version/home', array('version_id' => $last_version['version']['id']));
		}
	}
	itoast('', $url);
}
if ('display' == $do) {
	itoast('', $_W['siteroot'] . 'web/home.php');
}
if ('display' == $do || 'list' == $do) {
	$account_info = uni_account_create_info();
	$user_founder_info = table('users_founder_own_users')->getFounderByUid($_W['uid']);
	$user_founder_uid = !empty($user_founder_info) && !empty($user_founder_info['founder_uid']) ? $user_founder_info['founder_uid'] : 0;

	if (user_is_founder($_W['uid'], true)) {
		$founders = pdo_getall('users', array('founder_groupid' => 2), array('uid', 'username'), 'uid');
		$founder_id = intval($_GPC['founder_id']);
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 'list' == $do ? 24 : 20;
	$limit_num = intval($_GPC['limit_num']);
	$psize = $limit_num > 0 ? $limit_num : $psize;
	$type = in_array($_GPC['type'], array_keys($account_all_type_sign)) ? $_GPC['type'] : 'all';

	if ('all' == $type) {
		$condition = array_keys($account_all_type);
	} else {
		$condition = $account_all_type_sign[$type]['contain_type'];
	}

	$table = table('account');
	$table->searchWithType($condition);
	$keyword = safe_gpc_string($_GPC['keyword']);
	if (!empty($keyword)) {
		$table->searchWithKeyword($keyword);
	}
	$letter = safe_gpc_string($_GPC['letter']);
	if (!empty($letter) && '全部' != $letter) {
		$table->searchWithLetter($letter);
	}
	$search_role = in_array($_GPC['role'], array('owner', 'manager', 'operator')) ? $_GPC['role'] : '';
	if ($search_role) {
		$table->searchWithRole($search_role);
	}

	if ('all' == $type) {
		$total_list = array();
		foreach ($account_all_type as $account_type) {
			$total_list[$account_type['type_sign']] = 0;
		}

		if (!empty($founder_id)) {
			$table->searchWithViceFounder($founder_id);
		}
		$account_total = $table->searchAccounTotal(false);
		$table->searchWithType($condition);
		if (!empty($keyword)) {
			$table->searchWithKeyword($keyword);
		}
		if (!empty($letter) && '全部' != $letter) {
			$table->searchWithLetter($letter);
		}
		if ($search_role) {
			$table->searchWithRole($_GPC['role']);
		}
		foreach ($account_total as $row) {
			if (in_array($row['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
				$total_list['account'] += $row['total'];
			} elseif (in_array($row['type'], array(ACCOUNT_TYPE_XZAPP_NORMAL, ACCOUNT_TYPE_XZAPP_AUTH))) {
				$total_list['xzapp'] += $row['total'];
			} elseif (in_array($row['type'], array(ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_APP_AUTH))) {
				$total_list['wxapp'] += $row['total'];
			} else {
				foreach ($account_all_type as $type_key => $type_info) {
					if ($type_key == $row['type']) {
						$total_list[$type_info['type_sign']] += $row['total'];
					}
				}
			}
		}
	}
	if ('display' == $do) {
		$table->accountRankOrder();
		$table->accountUniacidOrder();
	} elseif ('list' == $do) {
		$orderby = $_GPC['orderby'] == 'initials' ? 'initials' : 'createtime';
		switch($orderby) {
			case 'createtime':
				$table->accountUniacidOrder();
				break;
			case 'initials':
				$table->accountInitialsOrder();
				break;
		}
	}
	$table->searchWithPage($pindex, $psize);
	$list = $table->searchAccountList(false);
	$total = $table->getLastQueryTotal();
	if (!empty($list)) {
		if (!user_is_founder($_W['uid'])) {
			$account_user_roles = table('uni_account_users')->where('uid', $_W['uid'])->getall('uniacid');
		}
		foreach ($list as $k => &$account) {
			$account = uni_fetch($account['uniacid']);
			$account['manageurl'] .= '&iscontroller=0';
			if (!in_array($account_user_roles[$account['uniacid']]['role'], array(ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_MANAGER)) && !$_W['isfounder']) {
				unset($account['manageurl']);
			}
			$account['list_type'] = 'account';
			$account['support_version'] = $account->supportVersion;
			$account['type_name'] = $account->typeName;
			$account['level'] = $account_all_type_sign[$account['type_sign']]['level'][$account['level']];
			$account['user_role'] = $account_user_roles[$account['uniacid']]['role'];
			if (ACCOUNT_MANAGE_NAME_CLERK == $account['user_role']) {
				unset($list[$k]);
				continue;
			}
			$account['is_star'] = table('users_operate_star')->getByUidUniacidModulename($_W['uid'], $account['uniacid'], '') ? 1 : 0;
			if (USER_ENDTIME_GROUP_EMPTY_TYPE != $account['endtime'] && USER_ENDTIME_GROUP_UNLIMIT_TYPE != $account['endtime'] && $account['endtime'] < TIMESTAMP) {
				$account['endtime_status'] = 1;
			} else {
				$account['endtime_status'] = 0;
			}

			if (WXAPP_TYPE_SIGN == $account->typeSign) {
				$version_info = miniapp_version_all($account['uniacid']);
				if (empty($version_info)) {
					continue;
				}
				foreach ($version_info as $version_key => $version_val) {
					$last_modules = $version_val['last_modules'] ? current($version_val['last_modules']) : array();
				}
				if (!empty($version_info[0]['modules'])) {
					$modules = current($version_info[0]['modules']);
					$account['need_upload'] = $last_modules['version'] < $modules['version'] ? 1 : 0;
				}
			}

			switch ($account['type']) {
				case ACCOUNT_TYPE_APP_NORMAL:
				case ACCOUNT_TYPE_APP_AUTH:
				case ACCOUNT_TYPE_ALIAPP_NORMAL:
				case ACCOUNT_TYPE_BAIDUAPP_NORMAL:
				case ACCOUNT_TYPE_TOUTIAOAPP_NORMAL:
					$account['versions'] = miniapp_get_some_lastversions($account['uniacid']);
					if (!empty($account['versions'])) {
						foreach ($account['versions'] as $version) {
							if (!empty($version['current'])) {
								$account['current_version'] = $version;
							}
						}
					}
					break;
				case ACCOUNT_TYPE_PHONEAPP_NORMAL:
					$account['versions'] = phoneapp_get_some_lastversions($account['uniacid']);
					if (!empty($account['versions'])) {
						foreach ($account['versions'] as $version) {
							if (!empty($version['current'])) {
								$account['current_version'] = $version;
							}
						}
					}
					break;
			}
		}
		if (!empty($list)) {
			$list = array_values($list);
		}
	}
	if ('list' == $do) {
		iajax(0, $list);
	}
	if ($_W['ispost']) {
		iajax(0, $list);
	}
	template('account/display');
}

if ('rank' == $do && $_W['isajax'] && $_W['ispost']) {
	$uniacid = intval($_GPC['uniacid']);
	if (!empty($uniacid)) {
		$exist = uni_fetch($uniacid);
		if (!$exist) {
			iajax(1, '账号信息不存在', '');
		}
	}
	uni_account_rank_top($uniacid);
	iajax(0, '更新成功！', '');
}

if ('switch' == $do) {
	$uniacid = intval($_GPC['uniacid']);
	$module_name = safe_gpc_string($_GPC['module_name']);
	if (!empty($uniacid)) {
		$role = permission_account_user_role($_W['uid'], $uniacid);
		if (empty($role) || ACCOUNT_MANAGE_NAME_CLERK == $role && empty($module_name)) {
			itoast('操作失败, 非法访问.', '', 'error');
		}
		$account_info = uni_fetch($uniacid);

		if (USER_ENDTIME_GROUP_EMPTY_TYPE != $account_info['endtime'] && USER_ENDTIME_GROUP_UNLIMIT_TYPE != $account_info['endtime'] && TIMESTAMP > $account_info['endtime'] && !user_is_founder($_W['uid'], true)) {
			$type_sign = $account_info->typeSign;
			$expired_message_settings = setting_load('account_expired_message');
			$expired_message_settings = $expired_message_settings['account_expired_message'][$type_sign];
			if (!empty($expired_message_settings) && !empty($expired_message_settings['status']) && !empty($expired_message_settings['message'])) {
				itoast($expired_message_settings['message']);
			} else {
				itoast('抱歉，您的平台账号服务已过期，请及时联系管理员');
			}
		}
		$type = $account_info['type'];
		
		$version_id = intval($_GPC['version_id']);
		if (STATUS_ON != $account_info->supportVersion) {
			if (empty($module_name)) {
				$url = url('home/welcome');
				if (ACCOUNT_TYPE_WEBAPP_NORMAL == $type) {
					$url = url('webapp/home/display');
				}
			} else {
				$url = url('home/welcome/ext', array('m' => $module_name));
				$main_uniacid = table('uni_link_uniacid')->getMainUniacid($uniacid, $module_name);
				if (!empty($main_uniacid)) {
					$uniacid = $main_uniacid;
					$account_info = uni_fetch($main_uniacid);
				}
			}
		} else {
			if (empty($version_id)) {
				if (ACCOUNT_TYPE_PHONEAPP_NORMAL == $type) {
					$versions = phoneapp_get_some_lastversions($uniacid);
				} else {
					$versions = miniapp_get_some_lastversions($uniacid);
				}
				foreach ($versions as $val) {
					if ($val['current']) {
						$version_id = $val['id'];
					}
				}
			}
			if (!empty($module_name) && !empty($version_id)) {
				$url = url('home/welcome/ext/', array('m' => $module_name));
				$main_uniacid = table('uni_link_uniacid')->getMainUniacid($uniacid, $module_name);
				if (!empty($main_uniacid)) {
					$uniacid = $main_uniacid;
					$account_info = uni_fetch($main_uniacid);
				} else {
					$url .= '&version_id=' . $version_id;
				}
			} else {
				miniapp_update_last_use_version($uniacid, $version_id);
				$url = url('miniapp/version/home', array('version_id' => $version_id));
			}
		}
		$url .= '&uniacid=' . $uniacid;
		if (empty($_GPC['switch_uniacid'])) {
			switch_save_account_display($uniacid);
		} else {
			switch_save_uniacid($uniacid);
		}
		if (!empty($_GPC['tohome'])) {
			$url .= '&tohome=1';
		}

		if (!empty($_GPC['miniapp_version_referer'])) {
			$url .= '&miniapp_version_referer=1';
		}

		if (!empty($_GPC['redirect'])) {
			$url = safe_gpc_url($_GPC['redirect']);
		}
		if (ACCOUNT_MANAGE_NAME_CLERK != $role) {
			user_save_operate_history(USERS_OPERATE_TYPE_ACCOUNT, $uniacid);
		}
		itoast('', $url);
	}
}
if ('history' == $do) {
	$limit_num = intval($_GPC['limit_num']);
	$limit_num = $limit_num > 0 ? $limit_num : 40;
	$history = user_load_operate_history($limit_num);
	if (empty($history)) {
		iajax(0, array());
	}
	$result = array();
	$keyword = safe_gpc_string($_GPC['keyword']);
	foreach ($history as $key => $item) {
		$operate = array();
		$account_info = uni_fetch($item['uniacid']);
		if (USERS_OPERATE_TYPE_ACCOUNT == $item['type'] && empty($account_info['isdeleted'])) {
			$operate = array(
				'list_type' => 'account',
				'name' => $account_info['name'],
				'uniacid' => $account_info['uniacid'],
				'type' => $account_info['type'],
				'type_name' => $account_info['type_name'],
				'level' => $account_all_type_sign[$account_info['type_sign']]['level'][$account_info['level']],
				'logo' => $account_info['logo'],
				'switchurl' => $account_info['switchurl'],
				'is_star' => $account_info['is_star'] ? 1 : 0,
			);
			if (!empty($keyword) && strpos($operate['name'], $keyword) === false) {
				continue;
			}
		} elseif (USERS_OPERATE_TYPE_MODULE == $item['type']) {
			$module_info = module_fetch($item['module_name']);
			if (empty($module_info)) {
				continue;
			}
			if (!empty($keyword) && strpos($module_info['title'], $keyword) === false) {
				continue;
			}
			$module_info['list_type'] = 'module';
			$module_info['is_star'] = table('users_operate_star')->getByUidUniacidModulename($_W['uid'], $item['uniacid'], $item['module_name']) ? 1 : 0;
			$module_info['switchurl'] = url('module/display/switch', array('module_name' => $item['module_name'], 'uniacid' => $item['uniacid']));
			$module_info['default_account'] = array(
				'name' => $account_info['name'],
				'uniacid' => $account_info['uniacid'],
				'type' => $account_info['type'],
				'logo' => $account_info['logo'],
			);
			$operate = $module_info;
		}
		if ($operate) {
			$result[] = $operate;
		}
	}
	iajax(0, $result);
}
if ('setting_star' == $do) {
	$type = intval($_GPC['type']);
	$uniacid = intval($_GPC['uniacid']);
	$module_name = safe_gpc_string($_GPC['module_name']);

	$result = user_save_operate_star($type, $uniacid, $module_name);
	if (is_error($result)) {
		iajax(-1, $result['message']);
	} else {
		iajax(0, '设置成功！');
	}
}
if ('setting_star_rank' == $do) {
	$change_ids = safe_gpc_array($_GPC['change_ids']);
	$users_operate_star_table = table('users_operate_star');
	$all_star = $users_operate_star_table->getALlByUid($_W['uid']);
	$all_star_num = count($all_star);
	if ($all_star_num != count($change_ids)) {
		iajax(-1, '参数不合法,非法请求！');
	}
	foreach ($change_ids as $id) {
		$if_exists = $users_operate_star_table->where('uid', $_W['uid'])->getById($id);
		if (!$if_exists) {
			iajax(-1, '当前用户没有设置该星标！');
			break;
		}
	}
	unset($id);
	$change_data = array();
	foreach ($change_ids as $id) {
		$change_data[] = array('id' => $id, 'rank' => $all_star_num);
		$all_star_num--;
	}
	foreach ($change_data as $data) {
		$result = $users_operate_star_table->where('id', $data['id'])->fill(array('rank' => $data['rank']))->save();
	}
	iajax(0, $result);
}
if ('list_star' == $do) {
	$limit_num = intval($_GPC['limit_num']);
	$limit_num = $limit_num > 0 ? $limit_num : 100;
	$list = user_load_operate_star($limit_num);
	if (empty($list)) {
		iajax(0, array());
	}
	$keyword = safe_gpc_string($_GPC['keyword']);
	foreach ($list as $key => $item) {
		$account_info = uni_fetch($item['uniacid']);
		if (USERS_OPERATE_TYPE_ACCOUNT == $item['type'] && empty($account_info['isdeleted'])) {
			if (!empty($keyword) && strpos($account_info['name'], $keyword) === false) {
				continue;
			}
			$result[] = array(
				'id' => $item['id'],
				'list_type' => 'account',
				'name' => $account_info['name'],
				'uniacid' => $account_info['uniacid'],
				'type' => $account_info['type'],
				'type_name' => $account_info['type_name'],
				'level' => $account_all_type_sign[$account_info['type_sign']]['level'][$account_info['level']],
				'logo' => $account_info['logo'],
				'switchurl' => $account_info['switchurl'],
				'manageurl' => $account_info['manageurl'],
				'is_star' => 1,
			);
		} elseif (USERS_OPERATE_TYPE_MODULE == $item['type']) {
			$module_info = module_fetch($item['module_name']);
			if (empty($module_info)) {
				continue;
			}
			if (!empty($keyword) && strpos($module_info['title'], $keyword) === false) {
				continue;
			}
			$module_info['id'] = $item['id'];
			$module_info['is_star'] = 1;
			$module_info['switchurl'] = url('module/display/switch', array('module_name' => $item['module_name'], 'uniacid' => $item['uniacid']));
			$module_info['default_account'] = array(
				'name' => $account_info['name'],
				'uniacid' => $account_info['uniacid'],
				'type' => $account_info['type'],
				'logo' => $account_info['logo'],
			);
			$module_info['list_type'] = 'module';
			$result[] = $module_info;
		}
	}
	iajax(0, $result);
}
if ('account_num' == $do) {
	$result = array('max_total' => 0, 'created_total' => 0, 'limit_total' => 0);
	if (user_is_founder($_W['uid'], true)) {
		iajax(0, array('max_total' => '不限', 'created_total' => '不限', 'limit_total' => '不限'));
	}
	$user_founder_info = table('users_founder_own_users')->getFounderByUid($_W['uid']);
	$account_num = permission_user_account_num();
	if ($user_founder_info) {
		$result['max_total'] = $account_num['max_total'] - $account_num['founder_limit_total'] > 0 ? ($account_num['founder_limit_total'] + $account_num['created_total']) : $account_num['max_total'];
		$result['created_total'] = $account_num['current_vice_founder_user_created_total'] < 0 ? 0 : $account_num['created_total'];
		$result['limit_total'] = $account_num['limit_total'] - $account_num['founder_limit_total'] > 0 ? $account_num['founder_limit_total'] : $account_num['limit_total'];
	} else {
		$result['max_total'] = max(0, $account_num['max_total']);
		$result['created_total'] = max(0, $account_num['created_total']);
		$result['limit_total'] = max(0, $account_num['limit_total']);
	}
	iajax(0, $result);
}
if ('welcome_link' == $do) {
	if (user_is_founder($_W['uid'], true)) {
		iajax(0, array());
	}
	$welcome_link_info = array(
		array('id' => WELCOME_DISPLAY_TYPE, 'name' => '用户欢迎页'),
		array('id' => PLATFORM_DISPLAY_TYPE, 'name' => '最后进入的平台或应用'),
	);
	$result = array(
		'user_welcome_link' => in_array($_W['user']['welcome_link'], array_column($welcome_link_info, 'id')) ? $_W['user']['welcome_link'] : WELCOME_DISPLAY_TYPE,
		'welcome_link' => $welcome_link_info,
	);
	iajax(0, $result);
}
if ('account_modules' == $do) {
	$uniacid = intval($_GPC['uniacid']);
	$result = array();
	$account_type_sign = table('account')->getByUniacid($uniacid);
	$account_type_sign = $account_all_type[$account_type_sign['type']]['type_sign'];
	$uni_user_accounts = uni_user_accounts($_W['uid'], $account_type_sign);
	if (!in_array($uniacid, array_keys($uni_user_accounts)) && !user_is_founder($_W['uid'], true)) {
		iajax(-1, '您没有该账号的权限！');
	}
	$account_modules = uni_modules_by_uniacid($uniacid);
	if (empty($account_modules)) {
		iajax(0, $result);
	}
	$account_info = uni_fetch($uniacid);
	if ($account_info->supportVersion) {
		$version_info = miniapp_fetch($uniacid);
		$version_modules = !empty($version_info['version']) && !empty($version_info['version']['modules']) ? array_keys($version_info['version']['modules']) : array();
	}
	$star_info = table('users_operate_star')->where('type', USERS_OPERATE_TYPE_MODULE)->where('uid', $_W['uid'])->where('uniacid', $uniacid)->where('module_name IN', array_keys($account_modules))->getall('module_name');
	foreach ($account_modules as $module) {
		if ($module['issystem'] || $module[$account_all_type[$account_info['type']]['module_support_name']]  != $account_all_type[$account_info['type']]['module_support_value']) {
			continue;
		}
		if (!empty($version_modules) && !in_array($module['name'], $version_modules)) {
			continue;
		}
		$module['switchurl'] = url('module/display/switch', array('module_name' => $module['name'], 'uniacid' => $uniacid));
		$module['is_star'] = $star_info[$module['name']] ? 1 : 0;
		$module['list_type'] = 'module';
		$module['default_account'] = array(
			'name' => $account_info['name'],
			'uniacid' => $account_info['uniacid'],
			'type' => $account_info['type'],
			'logo' => $account_info['logo'],
		);
		$result[] = $module;
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 40;
	$page_result = array_slice($result, ($pindex - 1) * $psize, $psize);

	$message = array(
		'total' => count($result),
		'page' => $pindex,
		'page_size' => $psize,
		'list' => $page_result
	);
	iajax(0, $message);
}
