<?php
/**
 * ALIPAY API: koubei.advert.delivery.discount.web.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-11 10:06:11
 */

namespace Alipay\Request;

class KoubeiAdvertDeliveryDiscountWebBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑广告投放优惠查询接口
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
