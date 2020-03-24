<?php
/**
 * ALIPAY API: alipay.marketing.campaign.discount.status.update request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-03 16:47:56
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDiscountStatusUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 优惠活动状态修改
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
