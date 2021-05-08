<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
$input = file_get_contents('php://input');
$isxml = true;
if (!empty($input) && empty($_GET['out_trade_no'])) {
	$obj = isimplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	$data = json_decode(json_encode($obj), true);
	if (empty($data)) {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => ''
		);
		echo array2xml($result);
		exit;
	}
	if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => empty($data['return_msg']) ? $data['err_code_des'] : $data['return_msg']
		);
		echo array2xml($result);
		exit;
	}
	$get = $data;
} else {
	$isxml = false;
	$get = $_GET;
}
load()->web('common');
load()->classs('coupon');
if ($get['attach'] == 'site_store') {
	$setting = setting_load('store_pay');
	$setting['payment']['wechat'] = $setting['store_pay']['wechat'];
} else {
	$_W['uniacid'] = $_W['weid'] = intval($get['attach']);
	$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
	$_W['acid'] = $_W['uniaccount']['acid'];
	$setting = uni_setting($_W['uniacid'], array('payment'));
}
if(is_array($setting['payment'])) {
	$wechat = $setting['payment']['wechat'];
	WeUtility::logging('pay', var_export($get, true));
	if(!empty($wechat)) {
		ksort($get);
		$string1 = '';
		foreach($get as $k => $v) {
			if($v != '' && $k != 'sign') {
				$string1 .= "{$k}={$v}&";
			}
		}

		if (intval($wechat['switch']) == 3) {
			$facilitator_setting = uni_setting($wechat['service'], array('payment'));
			$wechat['signkey'] = $facilitator_setting['payment']['wechat_facilitator']['signkey'];
		} else {
			$wechat['signkey'] = ($wechat['version'] == 1) ? $wechat['key'] : (!empty($wechat['apikey']) ? $wechat['apikey'] : $wechat['signkey']);  		}
		$sign = strtoupper(md5($string1 . "key={$wechat['signkey']}"));
		if($sign == $get['sign']) {
			$log = table('core_paylog')
				->where(array('uniontid' => $get['out_trade_no']))
				->get();
			if (intval($wechat['switch']) == PAYMENT_WECHAT_TYPE_SERVICE) {
				$get['openid'] = $log['openid'];
			}
						if(!empty($log) && $log['status'] == '0' && (($get['total_fee'] / 100) == $log['card_fee'])) {
				$log['tag'] = iunserializer($log['tag']);
				$log['tag']['transaction_id'] = $get['transaction_id'];
				$log['uid'] = $log['tag']['uid'];
				$record = array();
				$record['status'] = '1';
				$record['tag'] = iserializer($log['tag']);
				table('core_paylog')
					->where(array('plid' => $log['plid']))
					->fill($record)
					->save();
				$mix_pay_credit_log = table('core_paylog')
					->where(array(
						'module' => $log['module'],
						'tid' => $log['tid'],
						'uniacid' => $log['uniacid'],
						'type' => 'credit'
					))
					->get();
				if (!empty($mix_pay_credit_log)) {
					table('core_paylog')
						->where(array('plid' => $mix_pay_credit_log['plid']))
						->fill(array('status' => 1))
						->save();
					$log['fee'] = $mix_pay_credit_log['fee'] + $log['fee'];
					$log['card_fee'] = $mix_pay_credit_log['fee'] + $log['card_fee'];
					$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
					$credtis = mc_credit_fetch($log['uid']);
					mc_credit_update($log['uid'], $setting['creditbehaviors']['currency'], -$mix_pay_credit_log['fee'], array($log['uid'], '消费' . $setting['creditbehaviors']['currency'] . ':' . $fee));
				}
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

				if ($log['type'] == 'wxapp') {
					$site = WeUtility::createModuleWxapp($log['module']);
				} else {
					$site = WeUtility::createModuleSite($log['module']);
				}
				if(!is_error($site)) {
					$method = 'payResult';
					if (method_exists($site, $method)) {
						$ret = array();
						$ret['weid'] = $log['weid'];
						$ret['uniacid'] = $log['uniacid'];
						$ret['acid'] = $log['acid'];
						$ret['result'] = 'success';
						$ret['type'] = $log['type'];
						$ret['from'] = 'notify';
						$ret['tid'] = $log['tid'];
						$ret['uniontid'] = $log['uniontid'];
						$ret['transaction_id'] = $log['transaction_id'];
						$ret['trade_type'] = $get['trade_type'];
						$ret['follow'] = $get['is_subscribe'] == 'Y' ? 1 : 0;
						$ret['user'] = empty($get['openid']) ? $log['openid'] : $get['openid'];
						$ret['fee'] = $log['fee'];
						$ret['tag'] = $log['tag'];
						$ret['is_usecard'] = $log['is_usecard'];
						$ret['card_type'] = $log['card_type'];
						$ret['card_fee'] = $log['card_fee'];
						$ret['card_id'] = $log['card_id'];
						if(!empty($get['time_end'])) {
							$ret['paytime'] = strtotime($get['time_end']);
						}
						$site->$method($ret);
						if($isxml) {
							$result = array(
								'return_code' => 'SUCCESS',
								'return_msg' => 'OK'
							);
							echo array2xml($result);
							exit;
						} else {
							exit('success');
						}
					}
				}
			}
		}
	}
}
if($isxml) {
	$result = array(
		'return_code' => 'FAIL',
		'return_msg' => ''
	);
	echo array2xml($result);
	exit;
} else {
	exit('fail');
}
