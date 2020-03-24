<?php
/**
 * ALIPAY API: koubei.advert.delivery.discount.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-27 14:40:35
 */

namespace Alipay\Request;

class KoubeiAdvertDeliveryDiscountQueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑广告投放优惠信息查询接口
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
