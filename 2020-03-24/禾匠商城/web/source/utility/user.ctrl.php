<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('user');
$dos = array('browser');
$do = in_array($do, $dos) ? $do: 'browser';

if ($do == 'browser') {
	$mode = empty($_GPC['mode']) ? 'visible' : $_GPC['mode'];
	$mode = in_array($mode, array('invisible','visible')) ? $mode : 'visible';
	
	$callback = $_GPC['callback'];
	
	$uids = $_GPC['uids'];
	$uidArr = array();
	if(empty($uids)){
		$uids='';
	}else{
		foreach (explode(',', $uids) as $uid) {
			$uidArr[] = intval($uid);
		}
		$uids = implode(',', $uidArr);
	}
	$where = " WHERE status = '2' and type != '".ACCOUNT_OPERATE_CLERK."' AND founder_groupid != " . ACCOUNT_MANAGE_GROUP_VICE_FOUNDER;
	if($mode == 'invisible' && !empty($uids)){
		$where .= " AND uid not in ( {$uids} )";
	}
	$params = array();
	if(!empty($_GPC['keyword'])) {
		$where .= ' AND `username` LIKE :username';
		$params[':username'] = "%{$_GPC['keyword']}%";
	}
	if (user_is_vice_founder()) {
		$founder_users = table('users_founder_own_users')->getFounderOwnUsersList($_W['uid']);
		if (!empty($founder_users)) {
			$founder_users = implode(',', array_keys($founder_users));
			$where .= " AND `uid` in ($founder_users)";
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$total = 0;

	$list = pdo_fetchall("SELECT uid, groupid, username, remark FROM ".tablename('users')." {$where} ORDER BY `uid` LIMIT ".(($pindex - 1) * $psize).",{$psize}", $params);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('users'). $where , $params);
	$pager = pagination($total, $pindex, $psize, '', array('ajaxcallback'=>'null','mode'=>$mode,'uids'=>$uids));
	$usergroups = array();
	if (!empty($list)) {
		$group_ids = array();
		foreach ($list as $item) {
			if (!empty($item['groupid']) && !in_array($item['groupid'], $group_ids)) {
				$group_ids[] = $item['groupid'];
			}
		}
		if (!empty($group_ids)) {
			$usergroups = table('users_group')->getAllById($group_ids);
		}
	}
	template('utility/user-browser');
	exit;
}