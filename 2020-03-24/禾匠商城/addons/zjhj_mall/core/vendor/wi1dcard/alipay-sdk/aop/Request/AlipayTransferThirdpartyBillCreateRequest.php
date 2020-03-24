<?php
/**
 * ALIPAY API: alipay.transfer.thirdparty.bill.create request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-25 17:00:56
 */

namespace Alipay\Request;

class AlipayTransferThirdpartyBillCreateRequest extends AbstractAlipayRequest
{
    /**
     * 收款金额，单位：分
     **/
    private $amount;
    /**
     * 收款币种，默认为156（人民币）目前只允许转账人民币
     **/
    private $currency;
    /**
     * 扩展参数
     **/
    private $extParam;
    /**
     * 转账备注
     **/
    private $memo;
    /**
     * 合作方的支付宝帐号UID
     **/
    private $partnerId;
    /**
     * 外部系统收款方UID，付款人和收款人不能是同一个帐户
     **/
    private $payeeAccount;
    /**
     * （同payer_type所列举的）
     * 目前限制payer_type和payee_type必须一致
     **/
    private $payeeType;
    /**
     * 外部系统付款方的UID
     **/
    private $payerAccount;
    /**
     * 1-支付宝帐户
     * 2-淘宝帐户
     * 10001-新浪微博帐户
     * 10002-阿里云帐户
     * （1、2目前对外不可见、不可用）
     **/
    private $payerType;
    /**
     * 发起支付交易来源方定义的交易ID，用于将支付回执通知给来源方。不同来源方给出的ID可以重复，同一个来源方给出的ID唯一性由来源方保证。
     **/
    private $paymentId;
    /**
     * 支付来源
     * 10001-新浪微博
     * 10002-阿里云
     **/
    private $paymentSource;
    /**
     * 支付款项的标题
     **/
    private $title;

    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->apiParams['amount'] = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        $this->apiParams['currency'] = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setExtParam($extParam)
    {
        $this->extParam = $extParam;
        $this->apiParams['ext_param'] = $extParam;
    }

    public function getExtParam()
    {
        return $this->extParam;
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

    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->apiParams['partner_id'] = $partnerId;
    }

    public function getPartnerId()
    {
        return $this->partnerId;
    }

    public function setPayeeAccount($payeeAccount)
    {
        $this->payeeAccount = $payeeAccount;
        $this->apiParams['payee_account'] = $payeeAccount;
    }

    public function getPayeeAccount()
    {
        return $this->payeeAccount;
    }

    public function setPayeeType($payeeType)
    {
        $this->payeeType = $payeeType;
        $this->apiParams['payee_type'] = $payeeType;
    }

    public function getPayeeType()
    {
        return $this->payeeType;
    }

    public function setPayerAccount($payerAccount)
    {
        $this->payerAccount = $payerAccount;
        $this->apiParams['payer_account'] = $payerAccount;
    }

    public function getPayerAccount()
    {
        return $this->payerAccount;
    }

    public function setPayerType($payerType)
    {
        $this->payerType = $payerType;
        $this->apiParams['payer_type'] = $payerType;
    }

    public function getPayerType()
    {
        return $this->payerType;
    }

    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
        $this->apiParams['payment_id'] = $paymentId;
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function setPaymentSource($paymentSource)
    {
        $this->paymentSource = $paymentSource;
        $this->apiParams['payment_source'] = $paymentSource;
    }

    public function getPaymentSource()
    {
        return $this->paymentSource;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->apiParams['title'] = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
