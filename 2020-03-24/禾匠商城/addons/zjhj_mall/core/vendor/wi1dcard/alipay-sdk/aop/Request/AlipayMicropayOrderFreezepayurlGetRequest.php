<?php
/**
 * ALIPAY API: alipay.micropay.order.freezepayurl.get request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-06 17:52:18
 */

namespace Alipay\Request;

class AlipayMicropayOrderFreezepayurlGetRequest extends AbstractAlipayRequest
{
    /**
     * 冻结订单号,创建冻结订单时支付宝返回的
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
