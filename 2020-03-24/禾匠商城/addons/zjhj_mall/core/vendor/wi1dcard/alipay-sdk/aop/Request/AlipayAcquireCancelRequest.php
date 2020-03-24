<?php
/**
 * ALIPAY API: alipay.acquire.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-12 17:17:06
 */

namespace Alipay\Request;

class AlipayAcquireCancelRequest extends AbstractAlipayRequest
{
    /**
     * 操作员ID。
     **/
    private $operatorId;
    /**
     * 操作员的类型：
     * 0：支付宝操作员
     * 1：商户的操作员
     * 如果传入其它值或者为空，则默认设置为1
     **/
    private $operatorType;
    /**
     * 支付宝合作商户网站唯一订单号。
     **/
    private $outTradeNo;
    /**
     * 该交易在支付宝系统中的交易流水号。
     * 最短16位，最长64位。
     * 如果同时传了out_trade_no和trade_no，则以trade_no为准。
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

    public function setOperatorType($operatorType)
    {
        $this->operatorType = $operatorType;
        $this->apiParams['operator_type'] = $operatorType;
    }

    public function getOperatorType()
    {
        return $this->operatorType;
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
