<?php
/**
 * ALIPAY API: alipay.acquire.close request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-12 17:17:06
 */

namespace Alipay\Request;

class AlipayAcquireCloseRequest extends AbstractAlipayRequest
{
    /**
     * 卖家的操作员ID
     **/
    private $operatorId;
    /**
     * 支付宝合作商户网站唯一订单号
     **/
    private $outTradeNo;
    /**
     * 该交易在支付宝系统中的交易流水号。
     * 最短16位，最长64位。
     * 如果同时传了out_trade_no和trade_no，则以trade_no为准
     **/
    private $tradeNo;

    public function setOperatorId($operatorId)
    {
        $this->operatorId = $operatorId;
        $this->apiParams['operator_id'] = $operatorId;
    }

    public function getOperatorId()
    {
        return $this->operatorId;
    }

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
