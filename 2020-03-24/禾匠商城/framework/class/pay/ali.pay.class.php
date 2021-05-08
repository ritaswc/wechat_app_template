<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
class AliPay {
	public $alipay;
	public $refundlog_id;

	public function __construct($module = '') {
		global $_W;
		if (!empty($module) && 'store' == $module) {
			$setting = setting_load('store_pay');
			$this->setting = $setting['store_pay'];
		} else {
			$setting = uni_setting_load('payment', $_W['uniacid']);
			$this->setting = $setting['payment'];
		}
	}

	public function array2url($params) {
		$str = '';
		foreach ($params as $key => $val) {
			if (empty($val)) {
				continue;
			}
			$str .= "{$key}={$val}&";
		}
		$str = trim($str, '&');

		return $str;
	}

	public function bulidSign($params) {
		unset($params['sign']);
		ksort($params);
		$string = $this->array2url($params);
		$prikey = authcode($this->setting['ali_refund']['private_key'], 'DECODE');
		$res = openssl_get_privatekey($prikey);
		openssl_sign($string, $sign, $res, OPENSSL_ALGO_SHA256);
		openssl_free_key($res);
		$sign = base64_encode($sign);

		return $sign;
	}

	public function handleReufndResult($result) {
		global $_W;
		if (10000 == $result['code']) {
			WeUtility::logging('ali_refund', var_export($result, true));
			$pay_log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'uniontid' => $result['out_trade_no']));
			$refund_log = pdo_get('core_refundlog', array('uniacid' => $_W['uniacid'], 'id' => $this->refundlog_id));
			if (!empty($refund_log) && '0' == $refund_log['status'] && (($result['refund_fee']) == $refund_log['fee'])) {
				pdo_update('core_refundlog', array('status' => 1), array('id' => $refund_log['id']));
				$site = WeUtility::createModuleSite($pay_log['module']);
				if (!is_error($site)) {
					$method = 'refundResult';
					if (method_exists($site, $method)) {
						$ret = array();
						$ret['uniacid'] = $pay_log['uniacid'];
						$ret['result'] = 'success';
						$ret['type'] = $pay_log['type'];
						$ret['from'] = 'refund';
						$ret['tid'] = $pay_log['tid'];
						$ret['uniontid'] = $pay_log['uniontid'];
						$ret['refund_uniontid'] = $refund_log['refund_uniontid'];
						$ret['user'] = $pay_log['openid'];
						$ret['fee'] = $refund_log['fee'];
						$site->$method($ret);
						exit('success');
					}
				}
			}
		}
	}

	public function requestApi($url, $params) {
		load()->func('communication');
		$result = ihttp_post($url, $params);
		if (is_error($result)) {
			return $result;
		}
		$result['content'] = iconv('GBK', 'UTF-8//IGNORE', $result['content']);
		$result = json_decode($result['content'], true);
		if (!is_array($result)) {
			return error(-1, '返回数据错误');
		}
		if ($result['alipay_trade_refund_response']['code'] != 10000) {
			return error(-1, $result['alipay_trade_refund_response']['sub_msg']);
		}

		return $result['alipay_trade_refund_response'];
	}

	
	public function refund($params, $refundlog_id) {
		$this->refundlog_id = $refundlog_id;
		$params['sign'] = $this->bulidSign($params);

		return $this->requestApi('https://openapi.alipay.com/gateway.do', $params);
	}
}