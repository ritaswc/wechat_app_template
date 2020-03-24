<?php
/**
 * ALIPAY API: alipay.ebpp.bill.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-30 10:54:34
 */

namespace Alipay\Request;

class AlipayEbppBillGetRequest extends AbstractAlipayRequest
{
    /**
     * 输出机构的业务流水号，需要保证唯一性。
     **/
    private $merchantOrderNo;
    /**
     * 支付宝订单类型。公共事业缴纳JF,信用卡还款HK
     **/
    private $orderType;

    public function setMerchantOrderNo($merchantOrderNo)
    {
        $this->merchantOrderNo = $merchantOrderNo;
        $this->apiParams['merchant_order_no'] = $merchantOrderNo;
    }

    public function getMerchantOrderNo()
    {
        return $this->merchantOrderNo;
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
}
