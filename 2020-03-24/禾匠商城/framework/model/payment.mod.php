<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

define('ALIPAY_GATEWAY', 'https://mapi.alipay.com/gateway.do');


function alipay_build($params, $alipay = array(), $is_site_store = false) {
	global $_W;
	$tid = $params['uniontid'];
	$set = array();
	$set['service'] = 'alipay.wap.create.direct.pay.by.user';
	$set['partner'] = $alipay['partner'];
	$set['_input_charset'] = 'utf-8';
	$set['sign_type'] = 'MD5';
	$set['notify_url'] = $_W['siteroot'] . 'payment/alipay/notify.php';
	$set['return_url'] = $_W['siteroot'] . 'payment/alipay/return.php';
	$set['out_trade_no'] = $tid;
	$set['subject'] = $params['title'];
	$set['total_fee'] = $params['fee'];
	$set['seller_id'] = $alipay['account'];
	$set['payment_type'] = 1;
	$set['body'] = $is_site_store ? 'site_store' : $_W['uniacid'];
	if ($params['service'] == 'create_direct_pay_by_user') {
		$set['service'] = 'create_direct_pay_by_user';
		$set['seller_id'] = $alipay['partner'];
	} else {
		$set['app_pay'] = 'Y';
	}
	$prepares = array();
	foreach($set as $key => $value) {
		if($key != 'sign' && $key != 'sign_type') {
			$prepares[] = "{$key}={$value}";
		}
	}
	sort($prepares);
	$string = implode('&', $prepares);
	$string .= $alipay['secret'];
	$set['sign'] = md5($string);

	$response = ihttp_request(ALIPAY_GATEWAY . '?' . http_build_query($set, '', '&'), array(), array('CURLOPT_FOLLOWLOCATION' => 0));
	if (empty($response['headers']['Location'])) {
		exit(iconv('gbk', 'utf-8', $response['content']));
		return;
	}
	return array('url' => $response['headers']['Location']);
}

function wechat_proxy_build($params, $wechat) {
	global $_W;
	$uniacid = !empty($wechat['service']) ? $wechat['service'] : $wechat['borrow'];
	$oauth_account = uni_setting($uniacid, array('payment'));
	if (intval($wechat['switch']) == '2') {
		$_W['uniacid'] = $uniacid;
		$wechat['signkey'] = $oauth_account['payment']['wechat']['signkey'];
		$wechat['mchid'] = $oauth_account['payment']['wechat']['mchid'];
		unset($wechat['sub_mch_id']);
	} else {
		$wechat['signkey'] = $oauth_account['payment']['wechat_facilitator']['signkey'];
		$wechat['mchid'] = $oauth_account['payment']['wechat_facilitator']['mchid'];
	}
	$wechat['appid'] = pdo_getcolumn('account_wechats', array('uniacid' => $uniacid), 'key');
	$wechat['version'] = 2;
	return wechat_build($params, $wechat);
}

function wechat_build($params, $wechat, $is_site_store = false) {
	global $_W;
	load()->func('communication');
	if (empty($wechat['version']) && !empty($wechat['signkey'])) {
		$wechat['version'] = 1;
	}
	$wOpt = array();
	if ($wechat['version'] == 1) {
		$wOpt['appId'] = $wechat['appid'];
		$wOpt['timeStamp'] = strval(TIMESTAMP);
		$wOpt['nonceStr'] = random(8);
		$package = array();
		$package['bank_type'] = 'WX';
		$package['body'] = $params['title'];
		$package['attach'] = $_W['uniacid'];
		$package['partner'] = $wechat['partner'];
		$package['out_trade_no'] = $params['uniontid'];
		$package['total_fee'] = $params['fee'] * 100;
		$package['fee_type'] = '1';
		$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
		$package['spbill_create_ip'] = CLIENT_IP;
		$package['time_start'] = date('YmdHis', TIMESTAMP);
		$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
		$package['input_charset'] = 'UTF-8';
		if (!empty($wechat['sub_mch_id'])) {
			$package['sub_mch_id'] = $wechat['sub_mch_id'];
		}
		ksort($package);
		$string1 = '';
		foreach($package as $key => $v) {
			if (empty($v)) {
				unset($package[$key]);
				continue;
			}
			$string1 .= "{$key}={$v}&";
		}
		$string1 .= "key={$wechat['key']}";
		$sign = strtoupper(md5($string1));

		$string2 = '';
		foreach($package as $key => $v) {
			$v = urlencode($v);
			$string2 .= "{$key}={$v}&";
		}
		$string2 .= "sign={$sign}";
		$wOpt['package'] = $string2;

		$string = '';
		$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
		sort($keys);
		foreach($keys as $key) {
			$v = $wOpt[$key];
			if($key == 'appKey') {
				$v = $wechat['signkey'];
			}
			$key = strtolower($key);
			$string .= "{$key}={$v}&";
		}
		$string = rtrim($string, '&');
		$wOpt['signType'] = 'SHA1';
		$wOpt['paySign'] = sha1($string);
		return $wOpt;
	} else {
				if (!empty($params['user']) && is_numeric($params['user'])) {
			$params['user'] = mc_uid2openid($params['user']);
		}
		$package = array();
		$package['appid'] = $wechat['appid'];
		$package['mch_id'] = $wechat['mchid'];
		$package['nonce_str'] = random(8);
		$package['body'] = cutstr($params['title'], 26);
		$package['attach'] = $is_site_store ? 'site_store' : $_W['uniacid'];
		$package['out_trade_no'] = $params['uniontid'];
		$package['total_fee'] = $params['fee'] * 100;
		$package['spbill_create_ip'] = CLIENT_IP;
		$package['time_start'] = date('YmdHis', TIMESTAMP);
		$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
		$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
		$package['trade_type'] = 'JSAPI';
		if ($params['pay_way'] == 'web') {
			$package['trade_type'] = 'NATIVE';
			$package['product_id'] = $params['goodsid'];
		} else {
			$package['openid'] = empty($params['user']) ? $_W['fans']['from_user'] : $params['user'];
			if (!empty($wechat['sub_mch_id'])) {
				$package['sub_mch_id'] = $wechat['sub_mch_id'];
			}
			if (!empty($params['sub_user'])) {
				$package['sub_openid'] = $params['sub_user'];
				unset($package['openid']);
			}
		}
		ksort($package, SORT_STRING);
		$string1 = '';
		foreach($package as $key => $v) {
			if (empty($v)) {
				unset($package[$key]);
				continue;
			}
			$string1 .= "{$key}={$v}&";
		}
		$string1 .= "key={$wechat['signkey']}";
		$package['sign'] = strtoupper(md5($string1));
		$dat = array2xml($package);
		$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
		if (is_error($response)) {
			return $response;
		}
		$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
		if (strval($xml->return_code) == 'FAIL') {
			return error(-1, strval($xml->return_msg));
		}
		if (strval($xml->result_code) == 'FAIL') {
			return error(-1, strval($xml->err_code).': '.strval($xml->err_code_des));
		}
		$prepayid = $xml->prepay_id;
		$wOpt['appId'] = $wechat['appid'];
		$wOpt['timeStamp'] = strval(TIMESTAMP);
		$wOpt['nonceStr'] = random(8);
		$wOpt['package'] = 'prepay_id='.$prepayid;
		$wOpt['signType'] = 'MD5';
		if ($xml->trade_type == 'NATIVE') {
			$code_url = $xml->code_url;
			$wOpt['code_url'] = strval($code_url);
		}
		ksort($wOpt, SORT_STRING);
		foreach($wOpt as $key => $v) {
			$string .= "{$key}={$v}&";
		}
		$string .= "key={$wechat['signkey']}";
		$wOpt['paySign'] = strtoupper(md5($string));
		return $wOpt;
	}
}

function payment_proxy_pay_account() {
	global $_W;
	$setting = uni_setting($_W['uniacid'], array('payment'));
	$setting['payment']['wechat']['switch'] = intval($setting['payment']['wechat']['switch']);

	if ($setting['payment']['wechat']['switch'] == PAYMENT_WECHAT_TYPE_SERVICE) {
		$uniacid = intval($setting['payment']['wechat']['service']);
	} elseif ($setting['payment']['wechat']['switch'] == PAYMENT_WECHAT_TYPE_BORROW) {
		$uniacid = intval($setting['payment']['wechat']['borrow']);
	} else {
		$uniacid = 0;
	}
	$pay_account = uni_fetch($uniacid);
	if (empty($uniacid) || empty($pay_account)) {
		return error(1);
	}
	return WeAccount::createByUniacid($uniacid);
}
function payment_types($type = '') {
	$pay_types= array(
		'delivery' => '货到支付',
		'credit' => '余额支付',
		'mix' => '混合支付',
		'alipay' =>'支付宝支付',
		'wechat' => '微信支付',
		'wechat_facilitator' => '服务商支付',
		'unionpay' => '银联支付',
		'baifubao' => '百度钱包支付',
		'line' => '汇款支付',
		'jueqiymf' => '一码支付'
	);
	return !empty($pay_types[$type]) ? $pay_types[$type] : $pay_types;
}
function payment_setting() {
	global $_W;
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$pay_setting = is_array($setting['payment']) ? $setting['payment'] : array();
	if (empty($pay_setting['delivery'])) {
		$pay_setting['delivery'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
		);
	}
	if (empty($pay_setting['mix'])) {
		$pay_setting['mix'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
		);
	}
	if (empty($pay_setting['credit'])) {
		$pay_setting['credit'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
		);
	}
	if (empty($pay_setting['alipay'])) {
		$pay_setting['alipay'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
			'partner' => '',
			'secret' => '',
		);
	}
	if (empty($pay_setting['wechat'])) {
		$pay_setting['wechat'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
			'switch' => false,
		);
	} else {
		if (!in_array($pay_setting['wechat']['switch'], array('1'))) {
			unset($pay_setting['wechat']['signkey']);
		}
	}
	if (empty($pay_setting['unionpay'])) {
		$pay_setting['unionpay'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
			'merid' => '',
			'signcertpwd' => '',
		);
	}
	if (empty($pay_setting['baifubao'])) {
		$pay_setting['baifubao'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
			'mchid' => '',
			'signkey' => '',
		);
	}
	if (empty($pay_setting['line'])) {
		$pay_setting['line'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
			'message' => '',
		);
	}
	if (empty($pay_setting['jueqiymf'])) {
		$pay_setting['jueqiymf'] = array(
			'recharge_switch' => false,
			'pay_switch' => false,
			'url' => '',
			'mchid' => '',
		);
	}
	
		if (empty($pay_setting['wechat_facilitator'])) {
			$pay_setting['wechat_facilitator'] = array(
				'switch' => false,
				'mchid' => '',
				'signkey' => '',
			);
		}
	
		if (empty($_W['isfounder'])) {
		$user_account_list = pdo_getall('uni_account_users', array('uid' => $_W['uid']), array(), 'uniacid');
		$param['uniacid'] = array_keys($user_account_list);
	}
	$pay_setting['unionpay']['signcertexists'] = file_exists(IA_ROOT . '/attachment/unionpay/PM_'.$_W['uniacid'].'_acp.pfx');
	$no_recharge_types = array('delivery', 'credit', 'mix', 'line');
	$has_config_keys = array('pay_switch', 'recharge_switch', 'has_config', 'recharge_set', 'signcertexists', 'support_set');
	if ($pay_setting['wechat']['switch'] == 1) {
		if ($pay_setting['wechat']['version'] == 1) {
			unset($pay_setting['wechat']['mchid'], $pay_setting['wechat']['apikey']);
		} elseif ($pay_setting['wechat']['version'] == 2) {
			unset($pay_setting['wechat']['partner'], $pay_setting['wechat']['key'], $pay_setting['wechat']['signkey']);
		}
		unset($pay_setting['wechat']['borrow'], $pay_setting['wechat']['sub_mch_id'], $pay_setting['wechat']['service']);
	} elseif ($pay_setting['wechat']['switch'] == 2) {
		unset($pay_setting['wechat']['mchid'], $pay_setting['wechat']['apikey'], $pay_setting['wechat']['partner'], $pay_setting['wechat']['key'], $pay_setting['wechat']['signkey'], $pay_setting['wechat']['sub_mch_id'], $pay_setting['wechat']['service']);
	} elseif ($pay_setting['wechat']['switch'] == 3) {
		unset($pay_setting['wechat']['mchid'], $pay_setting['wechat']['apikey'], $pay_setting['wechat']['partner'], $pay_setting['wechat']['key'], $pay_setting['wechat']['signkey'], $pay_setting['wechat']['borrow']);
	}
	foreach ($pay_setting as $type => &$value) {
		if (empty($value) || !is_array($value)) {
			continue;
		}
		$value['has_config'] = true;
		$value['recharge_set'] = true;
		$value['support_set'] = true;
		if (in_array($type, $no_recharge_types)) {
			$value['recharge_set'] = false;
		}
		if (!empty($value['pay_switch']) || !empty($value['recharge_switch'])) {
			$value['support_set'] = false;
		}
		foreach ($value as $key => $val) {
			if (!in_array($key, $has_config_keys) && empty($val)) {
				$value['has_config'] = false;
				continue;
			}
		}
	}
	unset($value);
	return $pay_setting;
}