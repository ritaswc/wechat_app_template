<?php
/**
 * ALIPAY API: alipay.micropay.order.get request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-06 17:49:51
 */

namespace Alipay\Request;

class AlipayMicropayOrderGetRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝订单号，冻结流水号(创建冻结订单返回)
     **/
    private $alipayOrderNo;

    public function setAlipayOrderNo($alipayOrderNo)
    {
        $this->alipayOrderNo = $alipayOrderNo;
        $this->apiParams['alipay_order_no'] = $alipayOrderNo;
    }

    public function getAlipayOrderNo()
    {
        return $this->alipayOrderNo;
    }
}
