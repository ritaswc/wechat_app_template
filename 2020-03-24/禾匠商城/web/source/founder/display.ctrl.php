<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');

$dos = array('display', 'del');
$do = in_array($do, $dos) ? $do : 'display';

$founders = explode(',', $_W['config']['setting']['founder']);

if ('display' == $do) {
	$founder_groups = user_founder_group();

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$users_table = table('users');
	$users_table->searchWithFounder(ACCOUNT_MANAGE_GROUP_VICE_FOUNDER);

	$search = safe_gpc_string($_GPC['search']);
	if (!empty($search)) {
		$users_table->searchWithNameOrMobile($search);
	}

	$group_id = intval($_GPC['groupid']);
	if (!empty($group_id)) {
		$users_table->searchWithGroupId($group_id);
	}

	$users_table->searchWithPage($pindex, $psize);
	$users_table->searchWithViceFounder();
	$users = $users_table->getUsersList();
	$total = $users_table->getLastQueryTotal();
	$users = user_list_format($users);
	$pager = pagination($total, $pindex, $psize);
	template('founder/display');
}

if ('del' == $do) {
	if (!$_W['isajax'] || !$_W['ispost'] || !in_array($_W['uid'], $founders)) {
		iajax(-1, '非法操作！', url('founder/display'));
	}
	$uid = intval($_GPC['uid']);
	$uid_user = user_single($uid);
	if (in_array($uid, $founders)) {
		iajax(0, '访问错误, 无法操作站长.', url('founder/display'));
	}
	if (empty($uid_user)) {
		iajax(0, '未指定用户,无法删除.', url('founder/display'));
	}
	if (ACCOUNT_MANAGE_GROUP_VICE_FOUNDER != $uid_user['founder_groupid']) {
		iajax(0, '非法操作！', url('founder/display'));
	}
	user_delete($uid);
	iajax(0, '删除成功！', referer());
}
