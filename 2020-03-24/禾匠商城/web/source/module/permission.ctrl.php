<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display', 'post', 'delete');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

$module_name = trim($_GPC['m']);
$modulelist = uni_modules();
$module = $_W['current_module'] = $modulelist[$module_name];

if (empty($module)) {
	itoast('抱歉，你操作的模块不能被访问！');
}
if (!permission_check_account_user_module($module_name . '_permissions', $module_name)) {
	itoast('您没有权限进行该操作');
}

if ('display' == $do) {
	$user_permissions = module_clerk_info($module_name); 	$current_module_permission = module_permission_fetch($module_name); 
	$permission_name = array();
	if (!empty($current_module_permission)) {
		foreach ($current_module_permission as $key => $permission) {
			$permission_name[$permission['permission']] = $permission['title'];
		}
	}
	if (!empty($user_permissions)) {
		foreach ($user_permissions as $key => &$permission) {
			if (!empty($permission['permission'])) {
				$permission['permission'] = explode('|', $permission['permission']);
				foreach ($permission['permission'] as $k => $val) {
					$permission['permission'][$val] = $permission_name[$val];
					unset($permission['permission'][$k]);
				}
			}
		}
		unset($permission);
	}
	if ($_W['ispost'] && $_W['isajax']) {
		iajax(0, $user_permissions, '');
	}
	user_save_operate_history(USERS_OPERATE_TYPE_MODULE, $module_name);
}

if ('post' == $do) {
	$uid = intval($_GPC['uid']);
	$user = user_single($uid);
	$all_permission = array();
	$module_and_plugins = array($module_name);

	if (!empty($module['plugin_list'])) {
		foreach ($module['plugin_list'] as $item) {
			if (!empty($modulelist[$item])) {
				$module_and_plugins[] = $item;
			}
		}
	}

	foreach ($module_and_plugins as $key => $module_val) {
		$all_permission[$module_val]['info'] = module_fetch($module_val);
		$all_permission[$module_val]['permission'] = module_permission_fetch($module_val);
	}
	if (!empty($uid)) {
		foreach ($module_and_plugins as $key => $plugin) {
			$have_permission[$plugin] = permission_account_user_menu($uid, $_W['uniacid'], $plugin);
			foreach ($all_permission[$plugin]['permission'] as $key => $value) {
				$all_permission[$plugin]['permission'][$key]['checked'] = 0;
				if (in_array($value['permission'], $have_permission[$plugin]) || in_array('all', $have_permission[$plugin])) {
					$all_permission[$plugin]['permission'][$key]['checked'] = 1;
				}
				if (!empty($value['sub_permission'])) {
					foreach ($value['sub_permission'] as $sub_permission_key => $sub_permission_val) {
						if (in_array($sub_permission_val['permission'], $have_permission[$plugin])) {
							$all_permission[$plugin]['permission'][$key]['sub_permission'][$sub_permission_key]['checked'] = 1;
						}
					}
				}
			}
		}
		if (is_error($have_permission)) {
			itoast($have_permission['message']);
		}
	}

	if (checksubmit()) {
		if (empty($uid)) {
			$founders = explode(',', $_W['config']['setting']['founder']);
			$username = trim($_GPC['username']);
			$user = user_single(array('username' => $username));

			if (!empty($user)) {
				if (2 != $user['status']) {
					itoast('用户未通过审核或不存在', url('module/permission', array('m' => $module_name)), 'error');
				}
				$role = permission_account_user_role($user['uid'], $_W['uniacid']);
				if (in_array($role, array(
					ACCOUNT_MANAGE_NAME_FOUNDER,
					ACCOUNT_MANAGE_NAME_VICE_FOUNDER,
					ACCOUNT_MANAGE_NAME_OWNER,
					ACCOUNT_MANAGE_NAME_MANAGER,
					ACCOUNT_MANAGE_NAME_OPERATOR,
				))) {
					$role_title = user_role_title($role);
					itoast("该用户已是平台$role_title, 不可操作!", url('module/permission/post', array('m' => $module_name)), 'error');
				}
			} else {
				itoast('用户不存在', url('module/permission', array('m' => $module_name)), 'error');
			}
			$data = array('uniacid' => $_W['uniacid'], 'uid' => $user['uid'], 'type' => $module_name);
			$exists = pdo_get('users_permission', $data);
			if (is_array($exists) && !empty($exists)) {
				itoast('操作员已经存在！', url('module/permission', array('m' => $module_name)), 'error');
			}
			$uid = $user['uid'];
		}

		$permission = $_GPC['module_permission'];
		if (!empty($permission) && is_array($permission)) {
			$permission = safe_gpc_array($permission);
			foreach ($module_and_plugins as $name) {
				if (empty($permission[$name])) {
					$module_permission = '';
				} else {
					$module_permission = implode('|', array_unique($permission[$name]));
				}
				if (empty($module_permission) && !empty($have_permission[$name])) {
					pdo_delete('users_permission', array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'type' => $name));
					continue;
				}
				if (empty($module_permission)) {
					continue;
				}
				if (empty($have_permission[$name])) {
					pdo_insert('users_permission', array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'type' => $name, 'permission' => $module_permission));
				} else {
					pdo_update('users_permission', array('permission' => $module_permission), array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'type' => $name));
				}
			}
		} else {
			if (empty($all_permission[$module_name]['permission'])) {
				$data = array('uniacid' => $_W['uniacid'], 'uid' => $user['uid'], 'type' => $module_name);
				$exists = pdo_get('users_permission', $data);
				if (is_array($exists) && !empty($exists)) {
					itoast('操作员已经存在！', url('module/permission', array('m' => $module_name)), 'error');
				}
				$data['permission'] = 'all';
				pdo_insert('users_permission', $data);
			} else {
				foreach ($module_and_plugins as $name) {
					if (!empty($have_permission[$name]) && empty($all_permission[$module_name]['permission'])) {
						pdo_delete('users_permission', array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'type' => $name));
					}
				}
			}
		}

		$role = table('uni_account_users')->getUserRoleByUniacid($uid, $_W['uniacid']);
		if (empty($role)) {
			pdo_insert('uni_account_users', array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'role' => 'clerk'));
		} else {
			pdo_update('uni_account_users', array('role' => 'clerk'), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
		}
		itoast('操作成功', url('module/permission', array('m' => $module_name)), 'success');
	}
}

if ('delete' == $do) {
	$operator_id = intval($_GPC['uid']);
	if (empty($operator_id)) {
		itoast('参数错误', referer(), 'error');
	}
	$uniacid = intval($_GPC['uniacid']);
	if (!empty($uniacid) && !user_is_founder($_W['uid'], true)) {
		$role = permission_account_user_role($_W['uid'], $uniacid);
		if (!in_array($role, array(ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER))) {
			itoast('操作失败, 无权限', referer(), 'error');
		}
	}
	$uniacid = empty($uniacid) ? $_W['uniacid'] : $uniacid;

	$user = pdo_get('users', array('uid' => $operator_id), array('uid'));
	if (!empty($user)) {
		$module_info = module_fetch($module_name);
		$module_plugin_list = $module_info['plugin_list'];
		if (!empty($module_plugin_list)) {
			pdo_delete('users_permission', array('uid' => $operator_id, 'uniacid' => $uniacid, 'type in' => $module_plugin_list));
		}
		$delete_user_permission = pdo_delete('users_permission', array('uid' => $operator_id, 'type' => $module_name, 'uniacid' => $uniacid));

		$has_permission = pdo_get('users_permission', array('uid' => $operator_id, 'uniacid' => $uniacid));
		if (empty($has_permission)) {
						pdo_delete('uni_account_users', array('uid' => $operator_id, 'role' => 'clerk', 'uniacid' => $uniacid));
		}

		pdo_delete('users_lastuse', array('uid' => $operator_id, 'uniacid' => $uniacid, 'modulename' => $module_name));
	}
	itoast('删除成功', referer(), 'success');
}
template('module/permission');