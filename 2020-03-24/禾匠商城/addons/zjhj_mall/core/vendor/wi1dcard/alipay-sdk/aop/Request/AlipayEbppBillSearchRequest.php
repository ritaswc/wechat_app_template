<?php
/**
 * ALIPAY API: alipay.ebpp.bill.search request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-07 17:13:40
 */

namespace Alipay\Request;

class AlipayEbppBillSearchRequest extends AbstractAlipayRequest
{
    /**
     * 账单流水
     **/
    private $billKey;
    /**
     * 出账机构
     **/
    private $chargeInst;
    /**
     * 销账机构
     **/
    private $chargeoffInst;
    /**
     * 销账机构给出账机构分配的id
     **/
    private $companyId;
    /**
     * 必须以key value形式定义，转为json为格式：{"key1":"value1","key2":"value2","key3":"value3","key4":"value4"}
     * 后端会直接转换为MAP对象，转换异常会报参数格式错误
     **/
    private $extend;
    /**
     * 业务类型
     **/
    private $orderType;
    /**
     * 子业务类型
     **/
    private $subOrderType;

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

    public function setChargeoffInst($chargeoffInst)
    {
        $this->chargeoffInst = $chargeoffInst;
        $this->apiParams['chargeoff_inst'] = $chargeoffInst;
    }

    public function getChargeoffInst()
    {
        return $this->chargeoffInst;
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
        $this->apiParams['company_id'] = $companyId;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setExtend($extend)
    {
        $this->extend = $extend;
        $this->apiParams['extend'] = $extend;
    }

    public function getExtend()
    {
        return $this->extend;
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

    public function setSubOrderType($subOrderType)
    {
        $this->subOrderType = $subOrderType;
        $this->apiParams['sub_order_type'] = $subOrderType;
    }

    public function getSubOrderType()
    {
        return $this->subOrderType;
    }
}
