<?php
/**
 * ALIPAY API: zhima.merchant.order.rent.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 16:00:50
 */

namespace Alipay\Request;

class ZhimaMerchantOrderRentModifyRequest extends AbstractAlipayRequest
{
    /**
     * 信用借还订单修改接口
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
