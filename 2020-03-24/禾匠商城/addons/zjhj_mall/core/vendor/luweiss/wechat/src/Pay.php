<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/5/26
 * Time: 10:18
 */

namespace luweiss\wechat;


class Pay extends Base
{

    /**
     * 统一下单
     * @param array $args [
     *
     * 'body' => '商品描述',
     *
     * 'detail' => '商品详情，选填',
     *
     * 'attach' => '附加数据，选填',
     *
     * 'out_trade_no' => '商户订单号，最大长度32',
     *
     * 'total_fee' => '订单总金额，单位为分',
     *
     * 'notify_url' => '异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数',
     *
     * 'trade_type' => '交易类型，可选值：JSAPI，NATIVE，APP',
     *
     * 'product_id' => '商品ID，trade_type=NATIVE时，此参数必传',
     *
     * 'openid' => '用户标识，trade_type=JSAPI时，此参数必传',
     *
     * ]
     *
     * @return array|boolean
     *
     */
    public function unifiedOrder($args)
    {
        $args['appid'] = $this->wechat->appId;
        $args['mch_id'] = $this->wechat->mchId;
        $args['nonce_str'] = md5(uniqid());
        $args['sign_type'] = 'MD5';
        $args['spbill_create_ip'] = '127.0.0.1';
        $args['sign'] = $this->makeSign($args);
        $xml = DataTransform::arrayToXml($args);
        $api = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $this->wechat->curl->post($api, $xml);
        if (!$this->wechat->curl->response)
            return false;
        return DataTransform::xmlToArray($this->wechat->curl->response);
    }


    public function orderQuery($order_no)
    {
        $data = [
            'appid' => $this->wechat->appId,
            'mch_id' => $this->wechat->mchId,
            'out_trade_no' => $order_no,
            'nonce_str' => md5(uniqid()),
        ];
        $data['sign'] = $this->makeSign($data);
        $xml = DataTransform::arrayToXml($data);
        $api = "https://api.mch.weixin.qq.com/pay/orderquery";
        $this->wechat->curl->post($api, $xml);
        if (!$this->wechat->curl->response)
            return false;
        return DataTransform::xmlToArray($this->wechat->curl->response);
    }

    /**
     * 获取H5支付签名数据包
     * @param array $args [
     *
     * 'body' => '商品描述',
     *
     * 'detail' => '商品详情，选填',
     *
     * 'attach' => '附加数据，选填',
     *
     * 'out_trade_no' => '商户订单号，最大长度32',
     *
     * 'total_fee' => '订单总金额，单位为分',
     *
     * 'notify_url' => '异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数',
     *
     * 'openid' => '用户标识',
     *
     * ]
     *
     * @return array|null
     */
    public function getJsSignPackage($args)
    {
    }

    /**
     * 获取APP支付签名数据包
     * @param array $args [
     *
     * 'body' => '商品描述',
     *
     * 'detail' => '商品详情，选填',
     *
     * 'attach' => '附加数据，选填',
     *
     * 'out_trade_no' => '商户订单号，最大长度32',
     *
     * 'total_fee' => '订单总金额，单位为分',
     *
     * 'notify_url' => '异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数',
     *
     * ]
     *
     * @return array|null
     */
    public function getAppSignPackage($args)
    {
    }

    /**
     * 退款申请
     * @param array $args [
     *
     *
     * 'out_trade_no' => '商户订单号，最大长度32',
     *
     * 'out_refund_no' => '商户退款单号，最大长度32',
     *
     * 'total_fee' => '订单总金额，单位为分',
     *
     * 'refund_fee' => '退款总金额，单位为分',
     *
     * ]
     *
     * @return array|null
     */
    public function refund($args)
    {
        $args['appid'] = $this->wechat->appId;
        $args['mch_id'] = $this->wechat->mchId;
        $args['nonce_str'] = md5(uniqid());
        $args['op_user_id'] = $this->wechat->mchId;
        $args['sign'] = $this->makeSign($args);
        $xml = DataTransform::arrayToXml($args);
        $api = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $this->wechat->curl->post($api, $xml);
        if (!$this->wechat->curl->response)
            return false;
        return DataTransform::xmlToArray($this->wechat->curl->response);
    }


    /**
     * 企业付款，企业向用户支付
     * @param array $args [
     *
     *
     * 'partner_trade_no' => '商户订单号，最大长度32',
     *
     * 'openid' => '用户openid',
     *
     * 'amount' => '提现金额，单位为分',
     *
     * 'desc' => '企业付款操作说明，例如：提现',
     *
     * ]
     */
    public function transfers($args)
    {
        $args['mch_appid'] = $this->wechat->appId;
        $args['mchid'] = $this->wechat->mchId;
        $args['nonce_str'] = md5(uniqid());
        $args['check_name'] = 'NO_CHECK';
        $args['spbill_create_ip'] = '127.0.0.1';
        $args['sign'] = $this->makeSign($args);
        $xml = DataTransform::arrayToXml($args);
        $api = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        $this->wechat->curl->post($api, $xml);
        if (!$this->wechat->curl->response)
            return false;
        return DataTransform::xmlToArray($this->wechat->curl->response);
    }

    /**
     * 发放普通红包
     */
    public function sendRedPack($args)
    {
    }

    /**
     * 发放裂变红包
     */
    public function sendGroupRedPack($args)
    {
    }

    /**
     * MD5签名
     */
    public function makeSign($args)
    {
        if (isset($args['sign']))
            unset($args['sign']);
        ksort($args);
        foreach ($args as $i => $arg) {
            if ($args === null || $arg === '')
                unset($args[$i]);
        }
        $string = DataTransform::arrayToUrlParam($args, false);
        $string = $string . "&key={$this->wechat->apiKey}";
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }

    /**
     * @param $order_no
     * @return array|bool
     * 退款查询
     */
    public function refundQuery($order_no)
    {
        $data = [
            'appid' => $this->wechat->appId,
            'mch_id' => $this->wechat->mchId,
            'out_trade_no' => $order_no,
            'nonce_str' => md5(uniqid()),
        ];
        $data['sign'] = $this->makeSign($data);
        $xml = DataTransform::arrayToXml($data);
        $api = "https://api.mch.weixin.qq.com/pay/refundquery";
        $this->wechat->curl->post($api, $xml);
        if (!$this->wechat->curl->response)
            return false;
        return DataTransform::xmlToArray($this->wechat->curl->response);
    }

}