<?php
require_once __DIR__ . '/lib/WxPay.Api.php';

class WXPay {

    public function index() {
    	$result = $this->_postData();
        //         初始化值对象
        $input = new WxPayUnifiedOrder();
        //         文档提及的参数规范：商家名称-销售商品类目
        $input->SetBody($result['goods_name']);
        //         订单号应该是由小程序端传给服务端的，在用户下单时即生成，demo中取值是一个生成的时间戳
        $input->SetOut_trade_no($result['order_sn']);
        //         费用应该是由小程序端传给服务端的，在用户下单时告知服务端应付金额，demo中取值是1，即1分钱
        $input->SetTotal_fee($result['order_amount']*100);
        $input->SetNotify_url("https://shizhencaiyuan.com/groupAdmin.php/Home/Pay/wxAppletPay");
        $input->SetTrade_type("JSAPI");
        //         由小程序端传给服务端
        $input->SetOpenid($this->_getSession($result['code'])->openid);
        //         向微信统一下单，并返回order，它是一个array数组
        $order = WxPayApi::unifiedOrder($input);
        //         json化返回给小程序端
        header("Content-Type: application/json");
        echo $this->_getJsApiParameters($order);
    }

    private function _getJsApiParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
        || !array_key_exists("prepay_id", $UnifiedOrderResult)
        || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        $parameters = json_encode($jsapi->GetValues());
        return $parameters;
    }

    private function _getSession($code) {
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.WxPayConfig::APPID.'&secret='.WxPayConfig::APPSECRET.'&js_code='.$code.'&grant_type=authorization_code';
        $response = json_decode(file_get_contents($url));
        return $response;
    }

    private function _postData(){
    	$post = file_get_contents('php://input');
    	$post = urldecode($post);
    	$arr = explode('&', $post);
		$result = [];
		foreach ($arr as $key => &$value) {
			$value = explode('=', $value);
			$result[$value[0]] = $value[1];
		}
		return $result;
    }
}

$WxPay = new WXPay();
$WxPay->index();
