<?php
/**
 * ALIPAY API: alipay.micropay.order.direct.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-06 10:34:09
 */

namespace Alipay\Request;

class AlipayMicropayOrderDirectPayRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝订单号，冻结流水号.这个是创建冻结订单支付宝返回的
     **/
    private $alipayOrderNo;
    /**
     * 支付金额,区间必须在[0.01,30]，只能保留小数点后两位
     **/
    private $amount;
    /**
     * 支付备注
     **/
    private $memo;
    /**
     * 收款方的支付宝ID
     **/
    private $receiveUserId;
    /**
     * 本次转账的外部单据号（只能由字母和数字组成,maxlength=32
     **/
    private $transferOutOrderNo;

    public function setAlipayOrderNo($alipayOrderNo)
    {
        $this->alipayOrderNo = $alipayOrderNo;
        $this->apiParams['alipay_order_no'] = $alipayOrderNo;
    }

    public function getAlipayOrderNo()
    {
        return $this->alipayOrderNo;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->apiParams['amount'] = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setMemo($memo)
    {
        $this->memo = $memo;
        $this->apiParams['memo'] = $memo;
    }

    public function getMemo()
    {
        return $this->memo;
    }

    public function setReceiveUserId($receiveUserId)
    {
        $this->receiveUserId = $receiveUserId;
        $this->apiParams['receive_user_id'] = $receiveUserId;
    }

    public function getReceiveUserId()
    {
        return $this->receiveUserId;
    }

    public function setTransferOutOrderNo($transferOutOrderNo)
    {
        $this->transferOutOrderNo = $transferOutOrderNo;
        $this->apiParams['transfer_out_order_no'] = $transferOutOrderNo;
    }

    public function getTransferOutOrderNo()
    {
        return $this->transferOutOrderNo;
    }
}
