<?php
/**
 * ALIPAY API: zhima.merchant.order.rent.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-02 11:47:49
 */

namespace Alipay\Request;

class ZhimaMerchantOrderRentCreateRequest extends AbstractAlipayRequest
{
    /**
     * 信用借还创建订单
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
