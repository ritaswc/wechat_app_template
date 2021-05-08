<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');


function refund_order_can_refund($module, $tid) {
	global $_W;
	$params = array('tid' => $tid, 'module' => $module);
	if ($module != 'store') {
		$params['uniacid'] = $_W['uniacid'];
	}
	$paylog = pdo_get('core_paylog', $params);
	if (empty($paylog)) {
		return error(1, '订单不存在');
	}
	if ($paylog['status'] != 1) {
		return error(1, '此订单还未支付成功不可退款');
	}
	$refund_params = array('status' => 1, 'uniontid' => $paylog['uniontid']);
	if ($module != 'store') {
		$refund_params['uniacid'] = $_W['uniacid'];
	}
	$refund_amount = pdo_getcolumn('core_refundlog', $refund_params, 'SUM(fee)');
	if ($refund_amount >= $paylog['card_fee']) {
		return error(1, '订单已退款成功');
	}
	return true;
}


function refund_create_order($tid, $module, $fee = 0, $reason = '') {
	global $_W;
	load()->model('module');
	$order_can_refund = refund_order_can_refund($module, $tid);
	if (is_error($order_can_refund)) {
		return $order_can_refund;
	}
	$module_info = module_fetch($module);
	$moduleid =  empty($module_info['mid']) ? '000000' : sprintf("%06d", $module_info['mid']);
	$refund_uniontid = date('YmdHis') . $moduleid . random(8,1);
	$params = array('tid' => $tid, 'module' => $module);
	if ($module != 'store') {
		$params['uniacid'] = $_W['uniacid'];
	}
	$paylog = pdo_get('core_paylog', $params);
	$uniacid = $module == 'store' ? $paylog['uniacid'] : $_W['uniacid'];
	$refund = array (
		'uniacid' => $uniacid,
		'uniontid' => $paylog['uniontid'],
		'fee' => empty($fee) ? $paylog['card_fee'] : number_format($fee, 2, '.', ''),
		'status' => 0,
		'refund_uniontid' => $refund_uniontid,
		'reason' => safe_gpc_string($reason),
		'is_wish' => $paylog['is_wish'],
	);
	pdo_insert('core_refundlog', $refund);
	return pdo_insertid();
}


function refund($refund_id) {
	load()->classs('pay');
	global $_W;
	$refundlog = pdo_get('core_refundlog', array('id' => $refund_id));
	$params = array('uniontid' => $refundlog['uniontid']);
	$params['uniacid'] = $refundlog['is_wish'] == 1 ? $refundlog['uniacid'] : $_W['uniacid'];
	$paylog = pdo_get('core_paylog', $params);
	if ($paylog['type'] == 'wechat') {
		$refund_param = reufnd_wechat_build($refund_id, $refundlog['is_wish']);
		if (is_error($refund_param)) {
			return $refund_param;
		}
		if ($refundlog['is_wish'] == 1) {
			$module = 'store';
			$cert_file = ATTACHMENT_ROOT . 'store_wechat_refund_all.pem';
		} else {
			$module = '';
			$cert_file = ATTACHMENT_ROOT . $_W['uniacid'] . '_wechat_refund_all.pem';
		}

		$wechat = Pay::create('wechat', $module);
		$response = $wechat->refund($refund_param, $module);
		unlink($cert_file);
		if (is_error($response)) {
			pdo_update('core_refundlog', array('status' => '-1'), array('id' => $refund_id));
			return $response;
		} else {
			return $response;
		}
	} elseif ($paylog['type'] == 'alipay') {
		$refund_param = reufnd_ali_build($refund_id, $refundlog['is_wish']);
		if (is_error($refund_param)) {
			return $refund_param;
		}
		$module = $refundlog['is_wish'] == 1 ? 'store' : '';
		$ali = Pay::create('alipay', $module);
		$response = $ali->refund($refund_param, $refund_id);
		if (is_error($response)) {
			pdo_update('core_refundlog', array('status' => '-1'), array('id' => $refund_id));
			return $response;
		} else {
			return $response;
		}
	}
	return error(1, '此订单退款方式不存在');
}


function reufnd_ali_build($refund_id, $is_wish = 0) {
	global $_W;
	if ($is_wish == 1) {
		$setting = setting_load('store_pay');
		$refund_setting = $setting['store_pay']['ali_refund'];
	} else {
		$setting = uni_setting_load('payment', $_W['uniacid']);
		$refund_setting = $setting['payment']['ali_refund'];
	}
	if ($refund_setting['switch'] != 1) {
		return error(1, '未开启支付宝退款功能！');
	}
	if (empty($refund_setting['private_key'])) {
		return error(1, '缺少支付宝密钥证书！');
	}

	$refundlog = pdo_get('core_refundlog', array('id' => $refund_id));
	$uniacid = $is_wish == 1 ? $refundlog['uniacid'] : $_W['uniacid'];
	$paylog = pdo_get('core_paylog', array('uniacid' => $uniacid, 'uniontid' => $refundlog['uniontid']));
	$refund_param = array(
		'app_id' => $refund_setting['app_id'],
		'method' => 'alipay.trade.refund',
		'charset' => 'utf-8',
		'sign_type' => 'RSA2',
		'timestamp' => date('Y-m-d H:i:s'),
		'version' => '1.0',
		'biz_content' => array(
			'out_trade_no' => $refundlog['uniontid'],
			'refund_amount' => $refundlog['fee'],
			'refund_reason' => $refundlog['reason'],
		)
	);
	$refund_param['biz_content'] = json_encode($refund_param['biz_content']);
	return $refund_param;
}


function reufnd_wechat_build($refund_id, $is_wish = 0) {
	global $_W;
	if ($is_wish == 1) {
		$setting = setting_load('store_pay');
		$pay_setting = $setting['store_pay'];
		$refund_setting = $setting['store_pay']['wechat_refund'];
	} else {
		$setting = uni_setting_load('payment', $_W['uniacid']);
		$pay_setting = $setting['payment'];
		$refund_setting = $setting['payment']['wechat_refund'];
	}

	if ($refund_setting['switch'] != 1) {
		return error(1, '未开启微信退款功能！');
	}
	if (empty($refund_setting['key']) || empty($refund_setting['cert'])) {
		return error(1, '缺少微信证书！');
	}

	$refundlog = pdo_get('core_refundlog', array('id' => $refund_id));
	$uniacid = $is_wish == 1 ? $refundlog['uniacid'] : $_W['uniacid'];
	$paylog = pdo_get('core_paylog', array('uniacid' => $uniacid, 'uniontid' => $refundlog['uniontid']));
	$account = uni_fetch($uniacid);
	$refund_param = array(
		'appid' => $is_wish == 1 ? $pay_setting['wechat']['appid'] : $account['key'],
		'mch_id' => $pay_setting['wechat']['mchid'],
		'out_trade_no' => $refundlog['uniontid'],
		'out_refund_no' => $refundlog['refund_uniontid'],
		'total_fee' => $paylog['card_fee'] * 100,
		'refund_fee' => $refundlog['fee'] * 100,
		'nonce_str' => random(8),
		'refund_desc' => $refundlog['reason']
	);

	if ($pay_setting['wechat']['switch'] == PAYMENT_WECHAT_TYPE_SERVICE) {
		$refund_param['sub_mch_id'] = $pay_setting['wechat']['sub_mch_id'];
		$refund_param['sub_appid'] = $account['key'];
		$proxy_account = uni_fetch($pay_setting['wechat']['service']);
		$refund_param['appid'] = $proxy_account['key'];
		$refund_param['mch_id'] = $proxy_account['setting']['payment']['wechat_facilitator']['mchid'];
	}
	$cert = authcode($refund_setting['cert'], 'DECODE');
	$key = authcode($refund_setting['key'], 'DECODE');

	$cert_file = $is_wish == 1 ? 'store_wechat_refund_all.pem' : $_W['uniacid'] . '_wechat_refund_all.pem';
	file_put_contents(ATTACHMENT_ROOT . $cert_file, $cert . $key);
	return $refund_param;
}