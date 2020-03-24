<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
load()->app('common');
load()->app('template');

error_reporting(0);

if ($_GPC['orderno']) {
	$log = table('core_paylog')
		->where(array(
			'plid' => safe_gpc_int($_GPC['tid']),
			'fee' => safe_gpc_int($_GPC['fee'])
		))
		->get();
	if(!empty($log) && ($log['status'] == 0)) {
		$tag = array(
			'transaction_id' => safe_gpc_string($_GPC['orderno'])
		);
		$data = array(
			'status' => 1,
			'uniontid' => safe_gpc_string($_GPC['transId']),
			'openid' => safe_gpc_string($_GPC['uuid']),
			'tag' => iserializer($tag)
		);
		table('core_paylog')
			->where(array('tid' => $log['tid']))
			->fill($data)
			->save();
		$site = WeUtility::createModuleSite($log['module']);
		if (!is_error($site)) {
			$method = 'payResult';
			if (method_exists($site, $method)) {
				$ret = array();
				$ret['weid'] = $log['uniacid'];
				$ret['uniacid'] = $log['uniacid'];
				$ret['result'] = 'success';
				$ret['type'] = 'jueqiymf';
				$ret['from'] = 'notify';
				$ret['tid'] = $log['tid'];
				$ret['uniontid'] = $log['uniontid'];
				$ret['transaction_id'] = $_GPC['orderno'];
				$ret['user'] = $log['openid'];
				$ret['fee'] = $log['fee'];
				$ret['tag'] = $tag;
				$ret['is_usecard'] = $log['is_usecard'];
				$ret['card_type'] = $log['card_type'];
				$ret['card_fee'] = $log['card_fee'];
				$ret['card_id'] = $log['card_id'];
				exit($site->$method($ret));
				exit('success'); 
			}
		}
	}
}
exit('fail');