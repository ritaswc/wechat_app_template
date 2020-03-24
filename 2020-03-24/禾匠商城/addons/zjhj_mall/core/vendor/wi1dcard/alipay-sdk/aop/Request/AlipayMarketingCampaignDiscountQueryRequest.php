<?php
/**
 * ALIPAY API: alipay.marketing.campaign.discount.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-03 16:47:49
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDiscountQueryRequest extends AbstractAlipayRequest
{
    /**
     * 优惠活动查看
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
