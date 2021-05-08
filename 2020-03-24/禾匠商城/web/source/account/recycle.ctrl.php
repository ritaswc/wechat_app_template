<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display', 'recover', 'delete');
$do = in_array($do, $dos) ? $do : 'display';

if (!in_array($_W['role'], array(ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_FOUNDER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER))) {
	itoast('无权限操作！', referer(), 'error');
}

if ('display' == $do) {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$account_table = table('account');

	$account_type = $_GPC['account_type'];
	if (!empty($account_type)) {
		$account_table->searchWithType($account_all_type_sign[$account_type]['contain_type']);
	}

	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$account_table->searchWithKeyword($keyword);
	}

	$account_table->searchWithPage($pindex, $psize);
	$del_accounts = $account_table->searchAccountList(false, 0);
	$total = $account_table->getLastQueryTotal();
	$pager = pagination($total, $pindex, $psize);

	foreach ($del_accounts as &$account) {
		$account = uni_fetch($account['uniacid']);
		$account['end'] = 0 == $account['endtime'] ? '永久' : date('Y-m-d', $account['endtime']);
	}
	$del_accounts = array_values($del_accounts);
	if ($_W['isajax']) {
		iajax(0, array(
			'total' => $total,
			'page' => $pindex,
			'page_size' => $psize,
			'list' => $del_accounts
		));
	} else {
		template('account/recycle');
	}
}

if ('recover' == $do || 'delete' == $do) {
	$acid = intval($_GPC['acid']);
	$uniacid = intval($_GPC['uniacid']);
	$state = permission_account_user_role($_W['uid'], $uniacid);
	if (!in_array($state, array(ACCOUNT_MANAGE_NAME_FOUNDER, ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER))) {
		itoast('没有权限，请联系该平台账号的主管理员或网站创始人进行恢复操作！', referer(), 'error');
	}
}

if ('recover' == $do) {
	$account_info = permission_user_account_num();
	$account = uni_fetch($uniacid);
	$sign_limit = $account['type_sign'].'_limit';
	$founder_sign_limit = 'founder_' . $account['type_sign'] . '_limit';
	if (!(!empty($account_info[$sign_limit]) && (!empty($account_info[$founder_sign_limit]) && $_W['user']['owner_uid'] || empty($_W['user']['owner_uid'])) || !empty($account_info['store_' . $sign . '_limit']) || $_W['isfounder'] && !user_is_vice_founder())) {
		itoast('您所在用户组可添加的平台账号数量已达上限，请停用后再行恢复此平台账号！', referer(), 'error');
	}
	if (in_array($account['type_sign'], array(BAIDUAPP_TYPE_SIGN, TOUTIAOAPP_TYPE_SIGN))) {
		$appid = $account['appid'];
	} else {
		$appid = $account['key'];
	}
	if (!empty($appid)) {
		$hasAppid = uni_get_account_by_appid($appid, $account['type'], $account['uniacid']);
		if (!empty($hasAppid)) {
			itoast("该平台{$hasAppid['key_title']}已被其他平台使用, 请停用{$hasAppid['type_title']}[ {$hasAppid['name']} ]后再恢复.", referer(), 'error');
		}
	}
	if (!empty($uniacid)) {
		pdo_update('account', array('isdeleted' => 0), array('uniacid' => $uniacid));
		cache_delete(cache_system_key('uniaccount', array('uniacid' => $uniacid)));
	} else {
		pdo_update('account', array('isdeleted' => 0), array('acid' => $acid));
	}
	itoast('恢复成功', referer(), 'success');
}

if ('delete' == $do) {
	if (empty($_W['isajax']) || empty($_W['ispost'])) {
		iajax(0, '非法操作！', referer());
	}

	$jobid = account_delete($acid);
	if (user_is_founder($_W['uid'], true)) {
		$url = url('system/job/display', array('jobid' => $jobid));
	} else {
		$url = url('account/recycle', array('account_type' => ACCOUNT_TYPE));
	}
	iajax(0, '删除成功！', $url);
}
