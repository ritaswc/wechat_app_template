<?php
/**
 * ALIPAY API: alipay.ebpp.bill.add request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-22 21:21:47
 */

namespace Alipay\Request;

class AlipayEbppBillAddRequest extends AbstractAlipayRequest
{
    /**
     * 外部订单号
     **/
    private $bankBillNo;
    /**
     * 账单的账期，例如201203表示2012年3月的账单。
     **/
    private $billDate;
    /**
     * 账单单据号，例如水费单号，手机号，电费号，信用卡卡号。没有唯一性要求。
     **/
    private $billKey;
    /**
     * 支付宝给每个出账机构指定了一个对应的英文短名称来唯一表示该收费单位。
     **/
    private $chargeInst;
    /**
     * 扩展属性
     **/
    private $extendField;
    /**
     * 输出机构的业务流水号，需要保证唯一性
     **/
    private $merchantOrderNo;
    /**
     * 用户的手机号
     **/
    private $mobile;
    /**
     * 支付宝订单类型。公共事业缴纳JF,信用卡还款HK
     **/
    private $orderType;
    /**
     * 拥有该账单的用户姓名
     **/
    private $ownerName;
    /**
     * 缴费金额。用户支付的总金额。单位为：RMB Yuan。取值范围为[0.01，100000000.00]，精确到小数点后两位。
     **/
    private $payAmount;
    /**
     * 账单的服务费。
     **/
    private $serviceAmount;
    /**
     * 子业务类型是业务类型的下一级概念，例如：WATER表示JF下面的水费，ELECTRIC表示JF下面的电费，GAS表示JF下面的燃气费。
     **/
    private $subOrderType;
    /**
     * 交通违章地点，sub_order_type=TRAFFIC时填写。
     **/
    private $trafficLocation;
    /**
     * 违章行为，sub_order_type=TRAFFIC时填写。
     **/
    private $trafficRegulations;

    public function setBankBillNo($bankBillNo)
    {
        $this->bankBillNo = $bankBillNo;
        $this->apiParams['bank_bill_no'] = $bankBillNo;
    }

    public function getBankBillNo()
    {
        return $this->bankBillNo;
    }

    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;
        $this->apiParams['bill_date'] = $billDate;
    }

    public function getBillDate()
    {
        return $this->billDate;
    }

    public function setBillKey($billKey)
    {
        $this->billKey = $billKey;
        $this->apiParams['bill_key'] = $billKey;
    }

    public function getBillKey()
    {
        return $this->billKey;
    }

    public function setChargeInst($chargeInst)
    {
        $this->chargeInst = $chargeInst;
        $this->apiParams['charge_inst'] = $chargeInst;
    }

    public function getChargeInst()
    {
        return $this->chargeInst;
    }

    public function setExtendField($extendField)
    {
        $this->extendField = $extendField;
        $this->apiParams['extend_field'] = $extendField;
    }

    public function getExtendField()
    {
        return $this->extendField;
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

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        $this->apiParams['mobile'] = $mobile;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
        $this->apiParams['order_type'] = $orderType;
    }

    public function getOrderType()
    {
        return $this->orderType;
    }

    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
        $this->apiParams['owner_name'] = $ownerName;
    }

    public function getOwnerName()
    {
        return $this->ownerName;
    }

    public function setPayAmount($payAmount)
    {
        $this->payAmount = $payAmount;
        $this->apiParams['pay_amount'] = $payAmount;
    }

    public function getPayAmount()
    {
        return $this->payAmount;
    }

    public function setServiceAmount($serviceAmount)
    {
        $this->serviceAmount = $serviceAmount;
        $this->apiParams['service_amount'] = $serviceAmount;
    }

    public function getServiceAmount()
    {
        return $this->serviceAmount;
    }

    public function setSubOrderType($subOrderType)
    {
        $this->subOrderType = $subOrderType;
        $this->apiParams['sub_order_type'] = $subOrderType;
    }

    public function getSubOrderType()
    {
        return $this->subOrderType;
    }

    public function setTrafficLocation($trafficLocation)
    {
        $this->trafficLocation = $trafficLocation;
        $this->apiParams['traffic_location'] = $trafficLocation;
    }

    public function getTrafficLocation()
    {
        return $this->trafficLocation;
    }

    public function setTrafficRegulations($trafficRegulations)
    {
        $this->trafficRegulations = $trafficRegulations;
        $this->apiParams['traffic_regulations'] = $trafficRegulations;
    }

    public function getTrafficRegulations()
    {
        return $this->trafficRegulations;
    }
}
