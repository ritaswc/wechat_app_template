<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$ret = array();
if(empty($_GPC['module']) || empty($_GPC['sign']) || empty($_W['uniacid']) || empty($_GPC['action'])) {
	return false;
}

$name = trim($_GPC['module']);
$site = WeUtility::createModuleSite($name);
$return = $site->creditOperate($_GPC['sign'], $_GPC['action']);


if(empty($return)) {
	return false;
} elseif(empty($return['credit_total'])) {
	$ret['result'] = 'total-miss';
	moduleInit($_GPC['module'], $ret);
}

$ret = array();
$total = table('mc_handsel')
	->where(array(
		'uniacid' => $_W['uniacid'],
		'module' => safe_gpc_string($_GPC['module']),
		'sign' => safe_gpc_string($_GPC['sign'])
	))
	->getcolumn('SUM(credit_value)');
$credit_total = intval($return['credit_total']);
if($total >= $credit_total) {
	$ret['result'] = 'total-limit';
	moduleInit($_GPC['module'], $ret);
}

if(empty($_GPC['uid'])) {
	$uid = $_W['member']['uid'];
} else {
	$uid = intval($_GPC['uid']);
}

if(!empty($uid)) {
	$user = table('mc_members')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid
		))
		->getcolumn('uid');
	if(empty($user)) {
		$ret['result'] = 'uid-error';
		moduleInit($_GPC['module'], $ret);
	}
	if(!empty($_GPC['action'])) {
		$is_add = table('mc_handsel')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'touid' => $uid,
				'fromuid' => 0,
				'module' => safe_gpc_string($_GPC['module']),
				'sign' => safe_gpc_string($_GPC['sign']),
				'action' => safe_gpc_string($_GPC['action'])
			))
			->getcolumn('id');
		if(empty($is_add)) {
			$creditbehaviors = table('uni_settings')
				->where(array('uniacid' => $_W['uniacid']))
				->getcolumn('creditbehaviors');
			$creditbehaviors = iunserializer($creditbehaviors) ? iunserializer($creditbehaviors) : array();
			if(empty($creditbehaviors['activity'])) {
				$ret['result'] = 'creditset-miss';
				moduleInit($_GPC['module'], $ret);
			} else {
				$credittype = $creditbehaviors['activity'];
			}

			$data = array(
				'uniacid' => $_W['uniacid'],
				'touid' => $uid,
				'fromuid' => 0,
				'module' => safe_gpc_string($_GPC['module']),
				'sign' => safe_gpc_string($_GPC['sign']),
				'action' => safe_gpc_string($_GPC['action']),
				'credit_value' => intval($return['credit_value']),
				'createtime' => TIMESTAMP
			);
			table('mc_handsel')->fill($data)->save();
			$note = empty($_GPC['note']) ? '系统赠送积分' : safe_gpc_string($_GPC['note']);
			$log = array(
				'uid' => $uid,
				'credittype' => $credittype,
				'uniacid' => $_W['uniacid'],
				'num' => intval($return['credit_value']),
				'createtime' => TIMESTAMP,
				'operator' => 0,
				'remark' => $note
			);
			$credit_value = intval($return['credit_value']);
			mc_credit_update($uid, $credittype, $credit_value, $log);
			$ret['result'] = 'success';
			moduleInit($_GPC['module'], $ret);
		} else {
			$ret['result'] = 'repeat';
			moduleInit($_GPC['module'], $ret);
		}
	} else {
		$ret['result'] = 'action-miss';
		moduleInit($_GPC['module'], $ret);
	}
} else {
	$ret['result'] = 'uid-miss';
	moduleInit($_GPC['module'], $ret);
}


function moduleInit($name, $params = array()) {
	if(empty($name)) {
		return false;
	}
	$site = WeUtility::createModuleSite($name);
	if(!is_error($site)) {
		$method = 'shareResult';
		if(method_exists($site, $method)) {
			$site->$method($params);
			exit('success');
		}
	}
}


