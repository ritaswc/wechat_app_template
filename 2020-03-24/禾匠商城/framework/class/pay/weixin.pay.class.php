<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
class WeiXinPay extends pay {
	public $wxpay;

	public function __construct($module = '') {
		global $_W;

		if (!empty($module) && 'store' == $module) {
			$setting = setting_load('store_pay');
			$wxpay = $setting['store_pay']['wechat'];
		} else {
			$setting = uni_setting($_W['uniacid']);
			$wxpay = $setting['payment']['wechat'];
		}

		if (3 == intval($wxpay['switch'])) {
			$oauth_account = uni_setting($wxpay['service'], array('payment'));
			$oauth_acid = pdo_getcolumn('uni_account', array('uniacid' => $wxpay['service']), 'default_acid');
			$oauth_appid = pdo_getcolumn('account_wechats', array('acid' => $oauth_acid), 'key');
			$this->wxpay = array(
				'appid' => $oauth_appid,
				'mch_id' => $oauth_account['payment']['wechat_facilitator']['mchid'],
				'sub_mch_id' => $wxpay['sub_mch_id'],
				'key' => $oauth_account['payment']['wechat_facilitator']['signkey'],
				'notify_url' => $_W['siteroot'] . 'payment/wechat/notify.php',
			);
		} else {
			$this->wxpay = array(
				'appid' => 'store' == $module ? $wxpay['appid'] : $_W['account']['key'],
				'mch_id' => $wxpay['mchid'],
				'key' => !empty($wxpay['apikey']) ? $wxpay['apikey'] : $wxpay['signkey'],  				'notify_url' => $_W['siteroot'] . 'payment/wechat/notify.php',
			);
		}
	}

	public function array2url($params) {
		$str = '';
		$ignore = array('coupon_refund_fee', 'coupon_refund_count');
		foreach ($params as $key => $val) {
			if ((empty($val) || is_array($val)) && !in_array($key, $ignore)) {
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
		$string = $string . "&key={$this->wxpay['key']}";
		$string = md5($string);
		$result = strtoupper($string);

		return $result;
	}

	
	public function parseResult($result) {
		if ('<xml>' != substr($result, 0, 5)) {
			return $result;
		}
		$result = json_decode(json_encode(isimplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		if (!is_array($result)) {
			return error(-1, 'xml结构错误');
		}
		if ((isset($result['return_code']) && 'SUCCESS' != $result['return_code']) || (isset($result['result_code']) && 'SUCCESS' != $result['result_code']) || ('ERROR' == $result['err_code'] && !empty($result['err_code_des']))) {
			$msg = empty($result['return_msg']) ? $result['err_code_des'] : $result['return_msg'];

			return error(-1, $msg);
		}
		if (!empty($result['sign']) && $this->bulidsign($result) != $result['sign']) {
			return error(-1, '验证签名出错');
		}

		return $result;
	}

	
	public function requestApi($url, $params, $extra = array()) {
		load()->func('communication');
		$xml = array2xml($params);
		$response = ihttp_request($url, $xml, $extra);
		if (is_error($response)) {
			return $response;
		}
		$result = $this->parseResult($response['content']);

		return $result;
	}

	
	public function shortUrl($url) {
		$params = array(
			'appid' => $this->wxpay['appid'],
			'mch_id' => $this->wxpay['mch_id'],
			'long_url' => $url,
			'nonce_str' => random(32),
		);
		$params['sign'] = $this->bulidSign($params);
		$result = $this->requestApi('https://api.mch.weixin.qq.com/tools/shorturl', $params);
		if (is_error($result)) {
			return $result;
		}

		return $result['short_url'];
	}

	
	public function bulidNativePayurl($product_id, $short_url = true) {
		$params = array(
			'appid' => $this->wxpay['appid'],
			'mch_id' => $this->wxpay['mch_id'],
			'time_stamp' => TIMESTAMP,
			'nonce_str' => random(32),
			'product_id' => $product_id,
		);
		$params['sign'] = $this->bulidSign($params);
		$url = 'weixin://wxpay/bizpayurl?' . $this->array2url($params);
		if ($short_url) {
			$url = $this->shortUrl($url);
		}

		return $url;
	}

	
	public function paylog2NativeUrl($params) {
		$result = $this->buildPayLog($params);
		if (is_error($result)) {
			return $result;
		}
		$url = $this->bulidNativePayurl($result);
		if (is_error($url)) {
			return $url;
		}

		return array('url' => $url, 'product_id' => $result);
	}

	
	public function buildUnifiedOrder($params) {
				if (empty($params['out_trade_no'])) {
			return error(-1, '缺少统一支付接口必填参数out_trade_no:商户订单号');
		}
		if (empty($params['body'])) {
			return error(-1, '缺少统一支付接口必填参数body:商品描述');
		}
		if (empty($params['total_fee'])) {
			return error(-1, '缺少统一支付接口必填参数total_fee:总金额');
		}
		if (empty($params['trade_type'])) {
			return error(-1, '缺少统一支付接口必填参数trade_type:交易类型');
		}

				if ('JSAPI' == $params['trade_type'] && empty($params['openid'])) {
			return error(-1, '统一支付接口中，缺少必填参数openid！交易类型为JSAPI时，openid为必填参数！');
		}
		if ('NATIVE' == $params['trade_type'] && empty($params['product_id'])) {
			return error(-1, '统一支付接口中，缺少必填参数product_id！交易类型为NATIVE时，product_id为必填参数！');
		}

		if (empty($params['notify_url'])) {
			$params['notify_url'] = $this->wxpay['notify_url'];
		}
		$params['appid'] = $this->wxpay['appid'];
		$params['mch_id'] = $this->wxpay['mch_id'];
		$params['spbill_create_ip'] = CLIENT_IP;
		$params['nonce_str'] = random(32);
		$params['sign'] = $this->bulidSign($params);

		$result = $this->requestApi('https://api.mch.weixin.qq.com/pay/unifiedorder', $params);
		if (is_error($result)) {
			return $result;
		}

		return $result;
	}

	
	public function buildMicroOrder($params) {
				if (empty($params['out_trade_no'])) {
			return error(-1, '缺少刷卡支付接口必填参数out_trade_no:商户订单号');
		}
		if (empty($params['body'])) {
			return error(-1, '缺少刷卡支付接口必填参数body:商品描述');
		}
		if (empty($params['total_fee'])) {
			return error(-1, '缺少刷卡支付接口必填参数total_fee:总金额');
		}
		if (empty($params['auth_code'])) {
			return error(-1, '缺少刷卡支付接口必填参数auth_code:授权码');
		}
		$uniontid = $params['uniontid'];
		unset($params['uniontid']);

		$params['appid'] = $this->wxpay['appid'];
		$params['mch_id'] = $this->wxpay['mch_id'];
		$params['spbill_create_ip'] = CLIENT_IP;
		$params['nonce_str'] = random(32);
		if (!empty($this->wxpay['sub_mch_id'])) {
			$params['sub_mch_id'] = $this->wxpay['sub_mch_id'];
		}
		$params['sign'] = $this->bulidSign($params);
		$result = $this->requestApi('https://api.mch.weixin.qq.com/pay/micropay', $params);
		if (is_error($result)) {
			return $result;
		}
		if ('SUCCESS' != $result['result_code']) {
			return array('errno' => -10, 'message' => $result['err_code_des'], 'uniontid' => $uniontid);
		}

		return $result;
	}

	
	public function NoticeMicroSuccessOrder($result) {
		if (empty($result['out_trade_no'])) {
			return array('errno' => -1, 'message' => '交易单号错误');
		}
		$pay_log = pdo_get('core_paylog', array('uniontid' => $result['out_trade_no']));
		if (empty($pay_log)) {
			return array('errno' => -1, 'message' => '交易日志不存在');
		}
		$order = pdo_get('paycenter_order', array('uniontid' => $result['out_trade_no']));
		if (empty($order)) {
			return array('errno' => -1, 'message' => '交易订单不存在');
		}
		$data = array(
						'status' => 1,
			'openid' => $result['openid'],
		);
		pdo_update('core_paylog', $data, array('uniontid' => $result['out_trade_no']));
		$data['trade_type'] = strtolower($result['trade_type']);
		$data['paytime'] = strtotime($result['time_end']);
		$data['uniontid'] = $result['out_trade_no'];
		$data['follow'] = 'Y' == $result['is_subscribe'] ? 1 : 0;
		pdo_update('paycenter_order', $data, array('uniontid' => $result['out_trade_no']));
		if (!$order['credit_status'] && $order['uid'] > 0) {
			load()->model('mc');
			$member_credit = mc_credit_fetch($order['uid']);
			$message = '';
			if ($member_credit['credit1'] < $order['credit1']) {
				$message = '会员账户积分少于需扣除积分';
			}
			if ($member_credit['credit2'] < $order['credit2']) {
				$message = '会员账户余额少于需扣除余额';
			}
			if (!empty($message)) {
				return array('errno' => -10, 'message' => "该订单需要扣除会员积分:{$order['credit1']}, 扣除余额{$order['credit2']}.出错:{$message}.你需要和会员沟通解决该问题.");
			}
			if ($order['credit1'] > 0) {
				$status = mc_credit_update($order['uid'], 'credit1', -$order['credit1'], array(0, "会员刷卡消费,使用积分抵现,扣除{$order['credit1']}积分", 'system', $order['clerk_id'], $order['store_id'], $order['clerk_type']));
			}
			if ($order['credit2'] > 0) {
				$status = mc_credit_update($order['uid'], 'credit2', -$order['credit2'], array(0, "会员刷卡消费,使用余额支付,扣除{$order['credit2']}余额", 'system', $order['clerk_id'], $order['store_id'], $order['clerk_type']));
			}
		}
		pdo_update('paycenter_order', array('credit_status' => 1), array('id' => $order['id']));

		return true;
	}

	
	public function buildJsApiPrepayid($product_id) {
		$order = pdo_get('core_paylog', array('plid' => $product_id));
		if (empty($order)) {
			return error(-1, '订单不存在');
		}
		if (1 == $order['status']) {
			return error(-1, '该订单已经支付,请勿重复支付');
		}

				$jspai = array(
			'out_trade_no' => $order['uniontid'],
			'trade_type' => 'JSAPI',
			'openid' => $order['openid'],
			'body' => $order['body'],
			'total_fee' => $order['fee'] * 100,
			'attach' => $order['uniacid'],
		);
		$result = $this->buildUnifiedOrder($jspai);
		if (is_error($result)) {
			return $result;
		}
		$jspai = array(
			'appId' => $this->wxpay['appid'],
			'timeStamp' => TIMESTAMP,
			'nonceStr' => random(32),
			'package' => 'prepay_id=' . $result['prepay_id'],
			'signType' => 'MD5',
		);
		$jspai['paySign'] = $this->bulidSign($jspai);

		$jspai = <<<EOF
		<script type="text/javascript">
			document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest', {
						appId:'{$jspai['appId']}',
						timeStamp:'{$jspai['timeStamp']}',
						nonceStr:'{$jspai['nonceStr']}',
						package:'{$jspai['package']}',
						signType:'MD5',
						paySign:'{$jspai['paySign']}'
					},
					function(res){
						if(res.err_msg == 'get_brand_wcpay_request：ok' ) {
						 alert('支付成功')
						} else {
						}
					}
				);
			 }, false);
		</script>
EOF;

		return $jspai;
	}

	
	public function buildNativePrepayid($product_id) {
		$order = pdo_get('core_paylog', array('plid' => $product_id));
		if (empty($order)) {
			return error(-1, '订单不存在');
		}
		if (1 == $order['status']) {
			return error(-1, '该订单已经支付,请勿重复支付');
		}

		$data = array(
			'body' => $order['body'],
			'out_trade_no' => $order['uniontid'],
			'total_fee' => $order['fee'] * 100,
			'trade_type' => 'NATIVE',
			'product_id' => $order['plid'],
			'attach' => $order['uniacid'],
		);
		$result = $this->buildUnifiedOrder($data);
		if (is_error($result)) {
			return $result;
		}
		$params = array(
			'return_code' => 'SUCCESS',
			'appid' => $this->wxpay['appid'],
			'mch_id' => $this->wxpay['mch_id'],
			'prepay_id' => $result['prepay_id'],
			'nonce_str' => random(32),
			'result_code' => 'SUCCESS',
			'code_url' => $result['code_url'],
		);
		$params['sign'] = $this->bulidSign($params);

		return $params;
	}

	
	public function replyErrorNotify($msg) {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => $msg,
		);
		echo array2xml($result);
	}

	
	public function closeOrder($trade_no) {
		$params = array(
			'appid' => $this->wxpay['appid'],
			'mch_id' => $this->wxpay['mch_id'],
			'nonce_str' => random(32),
			'out_trade_no' => trim($trade_no),
		);
		$params['sign'] = $this->bulidSign($params);
		$result = $this->requestApi('https://api.mch.weixin.qq.com/pay/closeorder', $params);
		if (is_error($result)) {
			return $result;
		}
		if ('SUCCESS' == $result['result_code']) {
			pdo_update('paycenter_order', array('status' => 'CLOSED'), array('tradeno' => $result['out_trade_no']));
		}

		return true;
	}

	
	public function queryOrder($id, $type = 1) {
		$params = array(
			'appid' => $this->wxpay['appid'],
			'mch_id' => $this->wxpay['mch_id'],
			'nonce_str' => random(32),
		);
		if (1 == $type) {
			$params['transaction_id'] = $id;
		} else {
			$params['out_trade_no'] = $id;
		}
		$params['sign'] = $this->bulidSign($params);
		$result = $this->requestApi('https://api.mch.weixin.qq.com/pay/orderquery', $params);
		if (is_error($result)) {
			return $result;
		}
		if ('SUCCESS' != $result['result_code']) {
			return error(-1, $result['err_code_des']);
		}
		$result['total_fee'] = $result['total_fee'] / 100; 		return $result;
	}

	
	public function downloadBill($date, $type = 'ALL') {
		$params = array(
			'appid' => $this->wxpay['appid'],
			'mch_id' => $this->wxpay['mch_id'],
			'nonce_str' => random(32),
			'bill_date' => $date,
			'bill_type' => $type,
		);

		$params['sign'] = $this->bulidSign($params);
		$result = $this->requestApi('https://api.mch.weixin.qq.com/pay/downloadbill', $params);

		return $result;
	}

	
	public function refundOrder($date, $type = 'ALL') {
		$params = array(
			'appid' => $this->wxpay['appid'],
			'mch_id' => $this->wxpay['mch_id'],
			'nonce_str' => random(32),
			'bill_date' => $date,
			'bill_type' => $type,
		);
		$params['sign'] = $this->bulidSign($params);
		$result = $this->requestApi('https://api.mch.weixin.qq.com/pay/downloadbill', $params);

		return $result;
	}

	
	public function refund($params, $module = '') {
		global $_W;
		$params['sign'] = $this->bulidSign($params);

		if (!empty($module) && 'store' == $module) {
			$cert_root = ATTACHMENT_ROOT . 'store_wechat_refund_all.pem';
		} else {
			$cert_root = ATTACHMENT_ROOT . $_W['uniacid'] . '_wechat_refund_all.pem';
		}

		$result = $this->requestApi('https://api.mch.weixin.qq.com/secapi/pay/refund', $params, array(CURLOPT_SSLCERT => $cert_root));

		return $result;
	}
}