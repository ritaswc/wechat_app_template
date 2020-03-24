<?php
/**
 * ALIPAY API: koubei.marketing.campaign.intelligent.template.consult request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-29 11:02:45
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignIntelligentTemplateConsultRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销模板咨询
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
