<?php
/**
 * ALIPAY API: alipay.acquire.refund request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-12 17:17:03
 */

namespace Alipay\Request;

class AlipayAcquireRefundRequest extends AbstractAlipayRequest
{
    /**
     * 卖家的操作员ID。
     **/
    private $operatorId;
    /**
     * 操作员的类型：
     * 0：支付宝操作员
     * 1：商户的操作员
     * 如果传入其它值或者为空，则默认设置为1。
     **/
    private $operatorType;
    /**
     * 商户退款请求单号，用以标识本次交易的退款请求。
     * 如果不传入本参数，则以out_trade_no填充本参数的值。同时，认为本次请求为全额退款，要求退款金额和交易支付金额一致。
     **/
    private $outRequestNo;
    /**
     * 商户网站唯一订单号
     **/
    private $outTradeNo;
    /**
     * 业务关联ID集合，用于放置商户的退款单号、退款流水号等信息，json格式
     **/
    private $refIds;
    /**
     * 退款金额；退款金额不能大于订单金额，全额退款必须与订单金额一致。
     **/
    private $refundAmount;
    /**
     * 退款原因说明。
     **/
    private $refundReason;
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

    public function setOperatorType($operatorType)
    {
        $this->operatorType = $operatorType;
        $this->apiParams['operator_type'] = $operatorType;
    }

    public function getOperatorType()
    {
        return $this->operatorType;
    }

    public function setOutRequestNo($outRequestNo)
    {
        $this->outRequestNo = $outRequestNo;
        $this->apiParams['out_request_no'] = $outRequestNo;
    }

    public function getOutRequestNo()
    {
        return $this->outRequestNo;
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

    public function setRefIds($refIds)
    {
        $this->refIds = $refIds;
        $this->apiParams['ref_ids'] = $refIds;
    }

    public function getRefIds()
    {
        return $this->refIds;
    }

    public function setRefundAmount($refundAmount)
    {
        $this->refundAmount = $refundAmount;
        $this->apiParams['refund_amount'] = $refundAmount;
    }

    public function getRefundAmount()
    {
        return $this->refundAmount;
    }

    public function setRefundReason($refundReason)
    {
        $this->refundReason = $refundReason;
        $this->apiParams['refund_reason'] = $refundReason;
    }

    public function getRefundReason()
    {
        return $this->refundReason;
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
