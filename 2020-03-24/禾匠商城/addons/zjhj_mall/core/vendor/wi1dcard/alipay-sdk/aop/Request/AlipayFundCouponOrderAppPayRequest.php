<?php
/**
 * ALIPAY API: alipay.fund.coupon.order.app.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-07 20:52:45
 */

namespace Alipay\Request;

class AlipayFundCouponOrderAppPayRequest extends AbstractAlipayRequest
{
    /**
     * 受托无线端支付接口
     **/
    private $bizContent;

    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParams['biz_content'] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }
}
