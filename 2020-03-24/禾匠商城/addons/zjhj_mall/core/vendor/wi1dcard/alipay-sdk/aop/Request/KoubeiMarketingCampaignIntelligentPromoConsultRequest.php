<?php
/**
 * ALIPAY API: koubei.marketing.campaign.intelligent.promo.consult request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-23 18:30:42
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignIntelligentPromoConsultRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销活动咨询推荐接口
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
