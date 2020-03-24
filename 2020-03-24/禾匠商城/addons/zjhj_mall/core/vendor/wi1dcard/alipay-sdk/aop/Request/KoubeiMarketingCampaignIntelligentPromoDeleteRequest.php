<?php
/**
 * ALIPAY API: koubei.marketing.campaign.intelligent.promo.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-17 06:02:06
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignIntelligentPromoDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销活动下架
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
