<?php
/**
 * ALIPAY API: koubei.marketing.campaign.intelligent.promo.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-22 18:57:28
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignIntelligentPromoModifyRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销方案修改
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
