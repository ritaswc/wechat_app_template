<?php
/**
 * ALIPAY API: alipay.marketing.campaign.discount.budget.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-17 11:19:16
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDiscountBudgetCreateRequest extends AbstractAlipayRequest
{
    /**
     * 营销立减活动预算创建
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
