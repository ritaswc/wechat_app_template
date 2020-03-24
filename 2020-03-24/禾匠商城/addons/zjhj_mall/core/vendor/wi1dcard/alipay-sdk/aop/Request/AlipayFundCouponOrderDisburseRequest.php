<?php
/**
 * ALIPAY API: alipay.fund.coupon.order.disburse request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-21 16:49:08
 */

namespace Alipay\Request;

class AlipayFundCouponOrderDisburseRequest extends AbstractAlipayRequest
{
    /**
     * 受托打款接口
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
