<?php
/**
 * ALIPAY API: koubei.marketing.campaign.intelligent.shop.consult request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-29 11:03:57
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignIntelligentShopConsultRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销门店咨询
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
