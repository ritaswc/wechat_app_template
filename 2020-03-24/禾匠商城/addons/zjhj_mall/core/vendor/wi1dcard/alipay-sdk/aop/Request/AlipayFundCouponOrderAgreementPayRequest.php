<?php
/**
 * ALIPAY API: alipay.fund.coupon.order.agreement.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-21 16:49:19
 */

namespace Alipay\Request;

class AlipayFundCouponOrderAgreementPayRequest extends AbstractAlipayRequest
{
    /**
     * 红包协议支付接口
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
