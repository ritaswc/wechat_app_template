<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */


if (!defined("BFB_SDK_ROOT"))
{
	define("BFB_SDK_ROOT", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
 
require_once(BFB_SDK_ROOT . 'bfb_pay.cfg.php');

if (!function_exists('curl_init')) {
	exit('您的PHP没有安装 配置cURL扩展，请先安装配置cURL，具体方法可以上网查。');
}

if (!function_exists('json_decode')) {
	exit('您的PHP不支持JSON，请升级您的PHP版本。');
}


class bfb_sdk{
	public $err_msg;
	public $order_no;

	function __construct() {
	}

	
	function create_baifubao_pay_order_url($params, $url) {
		if (empty($params ['service_code']) || empty($params ['sp_no']) ||
				 empty($params ['order_create_time']) ||
				 empty($params ['order_no']) ||
				 empty($params ['goods_name']) ||
				 empty($params ['total_amount']) ||
				 empty($params ['currency']) ||
				 empty($params ['return_url']) ||
				 empty($params ['pay_type']) ||
				 empty($params ['input_charset']) ||
				 empty($params ['version']) ||
				 empty($params ['sign_method'])) {
			$this->log(sprintf('invalid params, params:[%s]', print_r($params, true)));
			return false;
		}
		if (!in_array($url, 
				array (
					sp_conf::BFB_PAY_DIRECT_LOGIN_URL,
					sp_conf::BFB_PAY_DIRECT_NO_LOGIN_URL,
					sp_conf::BFB_PAY_WAP_DIRECT_URL 
				))) {
			$this->log(
					sprintf('invalid url[%s], bfb just provide three kind of pay url', 
					$url));
			return false;
		}
		
		$pay_url = $url;
		
		if (false === ($sign = $this->make_sign($params))) {
			return false;
		}
		$this->order_no = $params ['order_no'];
		$params ['sign'] = $sign;
		$params_str = http_build_query($params);
		$this->log(
				sprintf('the params that create baifubao pay order is [%s]', 
						$params_str));
		
		return $pay_url . '?' . $params_str;
	}

	
	function check_bfb_pay_result_notify() {
				if (empty($_GET) || !isset($_GET ['sp_no']) || !isset(
				$_GET ['order_no']) || !isset($_GET ['bfb_order_no']) ||
				 !isset($_GET ['bfb_order_create_time']) ||
				 !isset($_GET ['pay_time']) || !isset($_GET ['pay_type']) ||
				 !isset($_GET ['total_amount']) || !isset($_GET ['fee_amount']) ||
				 !isset($_GET ['currency']) || !isset($_GET ['pay_result']) ||
				 !isset($_GET ['input_charset']) || !isset($_GET ['version']) ||
				 !isset($_GET ['sign']) || !isset($_GET ['sign_method'])) {
			$this->err_msg = 'return_url页面的请求的必选参数不足';
			$this->log(
					sprintf('missing the params of return_url page, params[%s]', 
							print_r($_GET)));
		}
		$arr_params = $_GET;
		$this->order_no = $arr_params ['order_no'];
				if (sp_conf::$SP_NO != $arr_params ['sp_no']) {
			$this->err_msg = '百付宝的支付结果通知中商户ID无效，该通知无效';
			$this->log(
					'the id in baifubao notify is wrong, this notify is invaild');
			return false;
		}
				if (sp_conf::BFB_PAY_RESULT_SUCCESS != $arr_params ['pay_result']) {
			$this->err_msg = '百付宝的支付结果通知中商户支付结果异常，该通知无效';
			$this->log(
					'the pay result in baifubao notify is wrong, this notify is invaild');
			return false;
		}
		
				if (false === $this->check_sign($arr_params)) {
			$this->err_msg = '百付宝后台通知签名校验失败';
			$this->log('baifubao notify sign failed');
			return false;
		}
		$this->log('baifubao notify sign success');
		
						
				$order_no = $arr_params ['order_no'];
		$order_state = $this->query_order_state($order_no);
		$this->log(sprintf('order state in sp server is [%s]', $order_state));
		if (sp_conf::SP_PAY_RESULT_WAITING == $order_state) {
			$this->log('the order state is right, the order is waiting for pay');
			return true;
		} elseif (sp_conf::SP_PAY_RESULT_SUCCESS == $order_state) {
			$this->log('the order state is wrong, this order has been paid');
			$this->err_msg = '订单[%s]已经处理，此百付宝后台支付通知为重复通知';
			return false;
		} else {
			$this->log(
					sprintf('the order state is wrong, it is [%s]', 
							$order_state));
			$this->err_msg = '订单[%s]状态异常';
			return false;
		}
		return false;
	}
	
	
	function notify_bfb() {
		$rep_str = "<html><head>" . sp_conf::BFB_NOTIFY_META .
				 "</head><body><h1>这是一个return_url页面</h1></body></html>";
		echo "$rep_str";
	}

	
	private function query_order_state($order_no) {
		
		return sp_conf::SP_PAY_RESULT_WAITING;
	}

	
	function query_baifubao_pay_result_by_order_no($order_no) {
		$params = array (
			'service_code' => sp_conf::BFB_QUERY_INTERFACE_SERVICE_ID, 			'sp_no' => sp_conf::$SP_NO,
			'order_no' => $order_no,
			'output_type' => sp_conf::BFB_INTERFACE_OUTPUT_FORMAT, 			'output_charset' => sp_conf::BFB_INTERFACE_ENCODING, 			'version' => sp_conf::BFB_INTERFACE_VERSION,
			'sign_method' => sp_conf::SIGN_METHOD_MD5
		);
	
				
		if (false === ($sign = $this->make_sign($params))) {
			$this->log(
					'make sign for query baifubao pay result interface failed');
			return false;
		}
		$params ['sign'] = $sign;
		$params_str = http_build_query($params);
		
		$query_url = sp_conf::BFB_QUERY_ORDER_URL . '?' . $params_str;
		$this->log(
				sprintf('the url of query baifubao pay result is [%s]', 
						$query_url));
		$content = $this->request($query_url);
		$retry = 0;
		while (empty($content) && $retry < sp_conf::BFB_QUERY_RETRY_TIME) {
			$content = $this->request($query_url);
			$retry++;
		}
		if (empty($content)) {
			$this->err_msg = '调用百付宝订单号查询接口失败';
			return false;
		}
		$this->log(
				sprintf('the result from baifubao query pay result is [%s]', 
						$content));
		$response_arr = json_decode(json_encode(isimplexml_load_string($content)), true);
				foreach ($response_arr as &$value) {
			if (empty($value) && is_array($value)) {
				$value = '';
			}
		}
		unset($value);
				if (empty($response_arr) || !isset($response_arr ['query_status']) ||
				 !isset($response_arr ['sp_no']) ||
				 !isset($response_arr ['order_no']) ||
				 !isset($response_arr ['bfb_order_no']) ||
				 !isset($response_arr ['bfb_order_create_time']) ||
				 !isset($response_arr ['pay_time']) ||
				 !isset($response_arr ['pay_type']) ||
				 !isset($response_arr ['goods_name']) ||
				 !isset($response_arr ['total_amount']) ||
				 !isset($response_arr ['fee_amount']) ||
				 !isset($response_arr ['currency']) ||
				 !isset($response_arr ['pay_result']) ||
				 !isset($response_arr ['sign']) ||
				 !isset($response_arr ['sign_method'])) {
			$this->err_msg = sprintf('百付宝的订单查询接口查询失败，返回数据为[%s]', 
					print_r($response_arr, true));
			return false;
		}
				if (0 != $response_arr ['query_status']) {
			$this->log(
					sprintf(
							'query the baifubao pay result interface faild, the query_status is [%s]', 
							$response_arr ['query_status']));
			$this->err_msg = sprintf('百付宝的订单查询接口查询失败，查询状态为[%s]', 
					$response_arr ['query_status']);
			return false;
		}
				if (sp_conf::$SP_NO != $response_arr ['sp_no']) {
			$this->log(
					'the sp_no returned from baifubao pay result interface is invaild');
			$this->err_msg = '百付宝的订单查询接口的响应数据中商户ID无效，该通知无效';
			return false;
		}
				if (sp_conf::BFB_PAY_RESULT_SUCCESS != $response_arr ['pay_result']) {
			$this->log(
					sprintf(
							'the pay result returned from baifubao pay result interface is invalid, is [%s]', 
							$response_arr ['pay_result']));
			$this->err_msg = '百付宝的订单查询接口的响应数据中商户支付结果异常，该通知无效';
			return false;
		}
		
				$response_arr ['goods_name'] = iconv("UTF-8", "GBK", 
				$response_arr ['goods_name']);
		if (isset($response_arr ['buyer_sp_username'])) {
			$response_arr ['buyer_sp_username'] = iconv("UTF-8", "GBK", 
					$response_arr ['buyer_sp_username']);
		}
				if (false === $this->check_sign($response_arr)) {
			$this->log(
					'sign the result returned from baifubao pay result interface failed');
			$this->err_msg = '百付宝订单查询接口响应数据签名校验失败';
			return false;
		}
		
		return print_r($response_arr, true);
	}

	
	private function make_sign($params) {
		if (is_array($params)) {
						if (ksort($params)) {
				if(false === ($params ['key'] = $this->get_sp_key())){
					return false;
				}
				$arr_temp = array ();
				foreach ($params as $key => $val) {
					$arr_temp [] = $key . '=' . $val;
				}
				$sign_str = implode('&', $arr_temp);
								if ($params ['sign_method'] == sp_conf::SIGN_METHOD_MD5) {
					return md5($sign_str);
				} else if ($params ['sign_method'] == sp_conf::SIGN_METHOD_SHA1) {
					return sha1($sign_str);
				} else{
					$this->log('unsupported sign method');
					$this->err_msg = '签名方法不支持';
					return false;
				}
			} else {
				$this->log('ksort failed');
				$this->err_msg = '表单参数数组排序失败';
				return false;
			}
		} else {
			$this->log('the params of making sign should be a array');
			$this->err_msg = '生成签名的参数必须是一个数组';
			return false;
		}
	}

	
	private function check_sign($params) {
		$sign = $params ['sign'];
		unset($params ['sign']);
		foreach ($params as &$value) {
			$value = urldecode($value); 		}
		unset($value);
		if (false !== ($my_sign = $this->make_sign($params))) {
			if (0 !== strcmp($my_sign, $sign)) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	
	private function get_sp_key() {
		return sp_conf::$SP_KEY_FILE;
		
	}

	
	function request($url) {
		$curl = curl_init(); 		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false); 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3); 		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		
		$res = curl_exec($curl); 		$err = curl_error($curl);
		
		if (false === $res || !empty($err)) {
			$info = curl_getinfo($curl);
			curl_close($curl);
			
			$this->log(
					sprintf(
							'curl the baifubao pay result interface failed, err_msg [%s]', 
							$info));
			$this->err_msg = $info;
			return false;
		}
		curl_close($curl); 		return $res;
	}

	
	function log($msg) {
		if(defined(sp_conf::$LOG_FILE)) {
			error_log(
					sprintf("[%s] [order_no: %s] : %s\n", date("Y-m-d H:i:s"), 
							$this->order_no, $msg));
		}
		else {
			error_log(
					sprintf("[%s] [order_no: %s] : %s\n", date("Y-m-d H:i:s"), 
							$this->order_no, $msg), 3, sp_conf::$LOG_FILE);
		}
	}
}

?>