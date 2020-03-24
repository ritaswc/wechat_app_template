<?php
/**
 * ALIPAY API: koubei.advert.delivery.discount.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-01 15:55:54
 */

namespace Alipay\Request;

class KoubeiAdvertDeliveryDiscountGetRequest extends AbstractAlipayRequest
{
    /**
     * 口碑广告推荐接口
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
