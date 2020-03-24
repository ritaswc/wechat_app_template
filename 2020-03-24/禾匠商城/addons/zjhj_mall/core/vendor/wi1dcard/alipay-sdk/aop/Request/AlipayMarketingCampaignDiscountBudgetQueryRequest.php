<?php
/**
 * ALIPAY API: alipay.marketing.campaign.discount.budget.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-21 23:42:43
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDiscountBudgetQueryRequest extends AbstractAlipayRequest
{
    /**
     * 营销立减活动预算查询
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
