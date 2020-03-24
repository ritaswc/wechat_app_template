<?php
/**
 * ALIPAY API: alipay.acquire.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-18 17:54:57
 */

namespace Alipay\Request;

class AlipayAcquireQueryRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝合作商户网站唯一订单号
     **/
    private $outTradeNo;
    /**
     * 该交易在支付宝系统中的交易流水号。
     * 最短16位，最长64位。
     * 如果同时传了out_trade_no和trade_no，则以trade_no为准。
     **/
    private $tradeNo;

    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
        $this->apiParams['out_trade_no'] = $outTradeNo;
    }

    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    public function setTradeNo($tradeNo)
    {
        $this->tradeNo = $tradeNo;
        $this->apiParams['trade_no'] = $tradeNo;
    }

    public function getTradeNo()
    {
        return $this->tradeNo;
    }
}
