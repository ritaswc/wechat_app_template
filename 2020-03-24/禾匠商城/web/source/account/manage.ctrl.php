<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('message');
load()->model('miniapp');

$dos = array('display', 'delete', 'account_detailinfo', 'account_create_info');
$do = in_array($_GPC['do'], $dos) ? $do : 'display';

if ('display' == $do) {
	if (!$_W['isfounder']) {
		itoast('', $_W['siteroot'] . 'web/home.php');
	}
	$message_id = intval($_GPC['message_id']);
	message_notice_read($message_id);

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$account_table = table('account');

	$account_type = $_GPC['account_type'];
	if (!empty($account_type) && in_array($account_type, array_keys($account_all_type_sign))) {
		$account_type = $account_all_type_sign[$account_type]['contain_type'];
		$account_table->searchWithType($account_type);
	}

	$order = safe_gpc_string($_GPC['order']);
	$account_table->accountUniacidOrder($order);

	$keyword = safe_gpc_string($_GPC['keyword']);
	if (!empty($keyword)) {
		$account_table->searchWithKeyword($keyword);
	}
	$account_table->searchWithPage($pindex, $psize);

	if (in_array(safe_gpc_string($_GPC['type']), array('expire', 'unexpire'))) {
		$expire_type = safe_gpc_string($_GPC['type']);
	}
	$list = $account_table->searchAccountList($expire_type);
	foreach ($account_all_type_sign as $type_sign => $type_value) {
		$type_accounts = uni_user_accounts($_W['uid'], $type_sign);
		if (!empty($type_accounts)) {
			$account_all_type_sign[$type_sign]['account_num'] = count($type_accounts);
		}
	}

	$list = array_values($list);
	$total = $account_table->getLastQueryTotal();
	$pager = pagination($total, $pindex, $psize);
	template('account/manage-display');
}

if ('account_create_info' == $do) {
	$result = uni_account_create_info();
	iajax(0, $result);
}

if ('account_detailinfo' == $do) {
	$uniacids = safe_gpc_array($_GPC['uniacids']);
	if (empty($uniacids)) {
		return array();
	}
	$account_detailinfo = array();
	foreach ($uniacids as $uniacid_value) {
		$uniacid = intval($uniacid_value['uniacid']);
		if ($uniacid <= 0) {
			continue;
		}
		$account = uni_fetch($uniacid);
		$account['owner_name'] = $account->owner['username'];
		$account['support_version'] = $account->supportVersion;
		$account['sms_num'] = !empty($account['setting']['notify']) ? $account['setting']['notify']['sms']['balance'] : 0;
		$account['end'] = USER_ENDTIME_GROUP_EMPTY_TYPE == $account['endtime'] || USER_ENDTIME_GROUP_UNLIMIT_TYPE == $account['endtime'] ? '永久' : date('Y-m-d', $account['endtime']);
		$account['manage_premission'] = in_array($account['current_user_role'], array(ACCOUNT_MANAGE_NAME_FOUNDER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER, ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_MANAGER));
		if ($account['support_version']) {
			$account['versions'] = miniapp_get_some_lastversions($uniacid);
			if (!empty($account['versions'])) {
				foreach ($account['versions'] as $version) {
					if (!empty($version['current'])) {
						$account['current_version'] = $version;
					}
				}
			}
		}
		$account_detailinfo[] = $account;
	}
	iajax(0, $account_detailinfo);
}

if ('delete' == $do) {
	$uniacids = empty($_GPC['uniacids']) && !empty($_GPC['uniacid']) ? array($_GPC['uniacid']) : $_GPC['uniacids'];
	if (!empty($uniacids)) {
		foreach ($uniacids as $uniacid) {
			$uniacid = intval($uniacid);
			$state = permission_account_user_role($_W['uid'], $uniacid);
			if (!in_array($state, array(ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_FOUNDER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER))) {
				continue;
			}

			if (!empty($uniacid)) {
				$account = pdo_get('account', array('uniacid' => $uniacid));
				if (empty($account)) {
					continue;
				}

				pdo_update('account', array('isdeleted' => 1), array('uniacid' => $uniacid));
				pdo_delete('uni_modules', array('uniacid' => $uniacid));
				pdo_delete('users_lastuse', array('uniacid' => $uniacid));
				pdo_delete('core_menu_shortcut', array('uniacid' => $uniacid));
				pdo_delete('uni_link_uniacid', array('link_uniacid' => $uniacid));
				if ($uniacid == $_W['uniacid']) {
					cache_delete(cache_system_key('last_account', array('switch' => $_GPC['__switch'], 'uid' => $_W['uid'])));
					isetcookie('__uniacid', '');
				}
				cache_delete(cache_system_key('user_accounts', array('type' => $account_all_type[$account['type']]['type_sign'], 'uid' => $_W['uid'])));
				cache_delete(cache_system_key('uniaccount', array('uniacid' => $uniacid)));
			}
		}
	}
	if (!$_W['isajax'] || !$_W['ispost']) {
		itoast('停用成功！，您可以在回收站中恢复', url('account/manage'));
	}
	iajax(0, '停用成功！，您可以在回收站中恢复', url('account/manage'));
}