<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

permission_check_account_user('mc_member_group');

$dos = array('display', 'change_group_level', 'save_group', 'get_group', 'set_default', 'del_group');
$do = in_array($do, $dos) ? $do : 'display';

if ('display' == $do) {
	$group_level_setting = table('uni_settings')
		->select('grouplevel')
		->where(array('uniacid' => $_W['uniacid']))
		->get();
	$group_level = empty($group_level_setting['grouplevel']) ? 0 : $group_level_setting['grouplevel'];

	$group_list = table('mc_groups')
		->where(array('uniacid' => $_W['uniacid']))
		->orderby(array('isdefault' => 'DESC', 'credit' => 'ASC'))
		->getall('groupid');
	$group_person_count = table('mc_members')
		->select(array('groupid', 'COUNT(*) AS num'))
		->where(array(
			'uniacid' => $_W['uniacid']
		))
		->groupby('groupid')
		->getall('groupid');
	$default_group = table('mc_groups')->where(array('uniacid' => $_W['uniacid'], 'isdefault' => 1))->get();
	if (empty($default_group)) {
		$default_group = array();
	}
}

if ('change_group_level' == $do) {
	$group_level = intval($_GPC['group_level']);
	table('uni_settings')
		->where(array('uniacid' => $_W['uniacid']))
		->fill(array('grouplevel' => $group_level))
		->save();
	cache_delete(cache_system_key('unisetting', array('uniacid' => $_W['uniacid'])));
	iajax(0, '');
}

if ('save_group' == $do) {
	$group = $_GPC['group'];
	if (empty($group)) {
		iajax(1, '编辑失败', '');
	}
	$data = array(
		'title' => safe_gpc_string($group['title']),
		'credit' => intval($group['credit']),
	);
	if (empty($data['title'])) {
		iajax(1, '请填写会员组名称', '');
	}
	$groupid = intval($group['groupid']);
	if (!empty($groupid)) {
		table('mc_groups')
			->where(array(
				'groupid' => $groupid,
				'uniacid' => $_W['uniacid']
			))
			->fill($data)
			->save();
		iajax(2, '修改成功', '');
	} else {
		$data['uniacid'] = $_W['uniacid'];
		$default_group = table('mc_groups')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'isdefault' => 1
			))
			->get();
		$data['isdefault'] = empty($default_group) ? 1 : 0;
		table('mc_groups')->fill($data)->save();
		$data['groupid'] = pdo_insertid();
		iajax(3, $data, '');
	}
}

if ('get_group' == $do) {
	$group_id = intval($_GPC['group_id']);
	if (empty($group_id)) {
		$data = array(
			'title' => '',
			'is_default' => 0,
			'credit' => 0,
		);
		iajax(0, $data, '');
	}
	$group_info = table('mc_groups')->getById($group_id);
	if (empty($group_info)) {
		iajax(1, '会员组不存在', '');
	} else {
		iajax(0, $group_info, '');
	}
}

if ('set_default' == $do) {
	$group_id = intval($_GPC['group_id']);
	table('mc_groups')
		->where(array('uniacid' => $_W['uniacid']))
		->fill(array('isdefault' => 0))
		->save();
	table('mc_groups')
		->where(array(
			'groupid' => $group_id,
			'uniacid' => $_W['uniacid']
		))
		->fill(array('isdefault' => 1))
		->save();
	iajax(0, '');
}

if ('del_group' == $do) {
	$group_id = intval($_GPC['group_id']);
	table('mc_groups')
		->where(array(
			'groupid' => $group_id,
			'uniacid' => $_W['uniacid']
		))
		->delete();
	iajax(0, '');
}
template('mc/group');
