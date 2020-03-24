<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');

$dos = array('display', 'post', 'del', 'save', 'info');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

if ('display' == $do) {
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize = 10;

	$users_group_table = table('users_group');
	$condition = '';
	$params = array();
	$name = safe_gpc_string($_GPC['name']);
	if (!empty($name)) {
		$users_group_table->searchWithNameLike($name);
	}

	if (user_is_vice_founder()) {
		$users_group_table->getOwnUsersGroupsList($_W['uid']);
	}
	$lists = $users_group_table->getUsersGroupList();

	$lists = user_group_format($lists);
	$total = $users_group_table->getLastQueryTotal();
	$pager = pagination($total, $pageindex, $pagesize);
	template('user/group-display');
}

if ('post' == $do) {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$group_info = pdo_get('users_group', array('id' => $id));
		$group_info['package'] = iunserializer($group_info['package']);
		if (!empty($group_info['package']) && in_array(-1, $group_info['package'])) {
			$group_info['check_all'] = true;
		} else {
			$checked_groups = pdo_getall('uni_group', array('uniacid' => 0, 'id' => $group_info['package']), array('id', 'name'), '', array('id DESC'));
		}
	}

	$packages = uni_groups();
	if (!empty($packages)) {
		foreach ($packages as $key => &$package_val) {
			if (!empty($group_info['package']) && in_array($key, $group_info['package'])) {
				$package_val['checked'] = true;
			} else {
				$package_val['checked'] = false;
			}
			if ($package_val['id'] == -1) {
				unset($packages[$key]);
			}
		}
		unset($package_val);
		$packages = array_values($packages);
	}

	$pagesize = 15;
	$pager = pagination(count($packages), 1, $pagesize, '', array('ajaxcallback' => true, 'callbackfuncname' => 'loadMore'));
	template('user/group-post');
}

if ('save' == $do) {
	$account_all_type = uni_account_type();
	$account_all_type_sign = array_keys(uni_account_type_sign());
	$group_info = safe_gpc_array($_GPC['group_info']);
	$user_group = array(
		'id' => intval($group_info['id']),
		'name' => $group_info['name'],
		'package' => $group_info['package'],
		'timelimit' => intval($group_info['timelimit']),
		'owner_uid' => 0
	);
	$max_type_all = 0;
	foreach ($account_all_type_sign as $account_type) {
		$maxtype = 'max' . $account_type;
		$user_group[$maxtype] = intval($group_info[$maxtype]);
		$max_type_all += $group_info[$maxtype];
	}

	if ($max_type_all <= 0) {
		iajax(-1, '创建账号个数，不能全部为0，至少要有一个!', referer());
	}

	$user_group_info = user_save_group($user_group);
	if (is_error($user_group_info)) {
		iajax(-1, $user_group_info['message'], referer());
	}
	cache_clean(cache_system_key('user_modules'));
	iajax(0, '用户组更新成功！', url('user/group/display'));
}

if ('del' == $do) {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		iajax(-1, '参数错误！');
	}
	$users = pdo_getall('users', array('groupid' => $id));
	if (!empty($users)) {
		$users_extra_limit_table = table('users_extra_limit');
		$group_info = pdo_get('users_group', array('id' => $id));
		foreach ($users as $user_info) {
			$extra_limit_info = $users_extra_limit_table->getExtraLimitByUid($user_info['uid']);
			if (empty($extra_limit_info)) {
				$result = user_update(array('uid' => $uid, 'groupid' => '', 'endtime' => 1));
			} else {
				$group_info_timelimit = $group_info['timelimit'];
				if (0 == $group_info_timelimit) {
					$end_time = !empty($extra_limit_info) && $extra_limit_info['timelimit'] > 0 ? strtotime($extra_limit_info['timelimit'] . ' days', $user_info['joindate']) : $user_info['joindate'];
				} else {
					$end_time = strtotime('-' . $group_info_timelimit . ' days', $user_info['endtime']);
				}
				$result = user_update(array('uid' => $user_info['uid'], 'groupid' => '', 'endtime' => $end_time));
			}
		}
	}

	$result = pdo_delete('users_group', array('id' => $id));
	$result_founder = pdo_delete('users_founder_own_users_groups', array('users_group_id' => $id));
	iajax(0, '删除成功！', url('user/group/display'));
}