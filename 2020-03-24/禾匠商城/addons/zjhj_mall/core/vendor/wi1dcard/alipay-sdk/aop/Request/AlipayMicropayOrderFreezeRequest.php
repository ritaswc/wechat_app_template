<?php
/**
 * ALIPAY API: alipay.micropay.order.freeze request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-06 17:49:00
 */

namespace Alipay\Request;

class AlipayMicropayOrderFreezeRequest extends AbstractAlipayRequest
{
    /**
     * 需要冻结金额，[0.01,2000]，必须是正数，最多只能保留小数点两位,单位是元
     **/
    private $amount;
    /**
     * 冻结资金的到期时间，超过此日期，冻结金会自动解冻,时间要求是:[当前时间+24h,订购时间-8h] .
     **/
    private $expireTime;
    /**
     * 冻结备注,maxLength=40
     **/
    private $memo;
    /**
     * 商户订单号,只能由字母和数字组成，最大长度32.此外部订单号与支付宝的冻结订单号对应,注意 应用id和订购者id和外部订单号必须保证唯一性。
     **/
    private $merchantOrderNo;
    /**
     * 在解冻转账的时候的支付方式: NO_CONFIRM：不需要付款确认，调用接口直接扣款 PAY_PASSWORD: 在转账需要付款方用支付密码确认，才可以转账成功
     **/
    private $payConfirm;

    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->apiParams['amount'] = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
        $this->apiParams['expire_time'] = $expireTime;
    }

    public function getExpireTime()
    {
        return $this->expireTime;
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

    public function setMerchantOrderNo($merchantOrderNo)
    {
        $this->merchantOrderNo = $merchantOrderNo;
        $this->apiParams['merchant_order_no'] = $merchantOrderNo;
    }

    public function getMerchantOrderNo()
    {
        return $this->merchantOrderNo;
    }

    public function setPayConfirm($payConfirm)
    {
        $this->payConfirm = $payConfirm;
        $this->apiParams['pay_confirm'] = $payConfirm;
    }

    public function getPayConfirm()
    {
        return $this->payConfirm;
    }
}
