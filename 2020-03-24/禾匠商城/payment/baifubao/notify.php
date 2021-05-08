<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
$_GPC['i'] = !empty($_GPC['i']) ? intval($_GPC['i']) : intval($_GET['extra']);
require '../../app/common/bootstrap.app.inc.php';
load()->app('template');
load()->web('common');
load()->classs('coupon');
$_W['uniacid'] = $_W['weid'] = intval($_GPC['i']);
$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
$_W['acid'] = $_W['uniaccount']['acid'];
$setting = uni_setting($_W['uniacid'], array('payment'));
if(!is_array($setting['payment'])) {
	exit('没有设定支付参数.');
}
$payment = $setting['payment']['baifubao'];

require 'bfb_sdk.php';
$bfb_sdk = new bfb_sdk();
if (!empty($_GPC['pay_result']) && $_GPC['pay_result'] == '1') {
	if (true === $bfb_sdk->check_bfb_pay_result_notify()) {
		WeUtility::logging('pay', var_export($_GPC, true));
		$log = table('core_paylog')
			->where(array('uniontid' => safe_gpc_string($_GPC['order_no'])))
			->get();
		if(!empty($log) && $log['status'] == '0' && (($_GPC['total_amount'] / 100) == $log['card_fee'])) {
			$log['tag'] = iunserializer($log['tag']);
			$log['tag']['bfb_order_no'] = $_POST['bfb_order_no'];
			$record = array();
			$record['status'] = 1;
			$record['tag'] = iserializer($log['tag']);
			table('core_paylog')
				->where(array('plid' => $log['plid']))
				->fill($record)
				->save();
			if ($log['is_usecard'] == 1 && !empty($log['encrypt_code'])) {
				$coupon_info = table('coupon')
					->where(array('id' => $log['card_id']))
					->select('id')
					->get();
				$coupon_record = table('coupon_record')
					->where(array(
						'code' => $log['encrypt_code'],
						'status' => '1'
					))
					->get();
				load()->model('activity');
			 	$status = activity_coupon_use($coupon_info['id'], $coupon_record['id'], $log['module']);
			}

			$site = WeUtility::createModuleSite($log['module']);
			if(!is_error($site)) {
				$method = 'payResult';
				if (method_exists($site, $method)) {
					$ret = array();
					$ret['weid'] = $log['uniacid'];
					$ret['uniacid'] = $log['uniacid'];
					$ret['result'] = 'success';
					$ret['type'] = $log['type'];
					$ret['from'] = 'notify';
					$ret['tid'] = $log['tid'];
					$ret['uniontid'] = $log['uniontid'];
					$ret['user'] = $log['openid'];
					$ret['fee'] = $log['fee'];
					$ret['tag'] = $log['tag'];
					$site->$method($ret);
					$bfb_sdk->notify_bfb();
					exit('success');
				}
			}
		}
	}
}
$bfb_sdk->notify_bfb();
exit('fail');