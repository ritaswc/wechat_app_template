<?php
/**
 * ALIPAY API: koubei.marketing.campaign.intelligent.promo.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-18 11:42:29
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignIntelligentPromoCreateRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销方案创建
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
