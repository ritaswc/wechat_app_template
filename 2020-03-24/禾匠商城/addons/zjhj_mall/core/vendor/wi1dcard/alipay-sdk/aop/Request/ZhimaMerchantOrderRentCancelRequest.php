<?php
/**
 * ALIPAY API: zhima.merchant.order.rent.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2017-05-25 14:34:16
 */

namespace Alipay\Request;

class ZhimaMerchantOrderRentCancelRequest extends AbstractAlipayRequest
{
    /**
     * 信用借还撤销订单
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
