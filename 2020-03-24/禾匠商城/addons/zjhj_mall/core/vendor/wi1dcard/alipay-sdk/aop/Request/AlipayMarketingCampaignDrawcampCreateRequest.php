<?php
/**
 * ALIPAY API: alipay.marketing.campaign.drawcamp.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-23 14:22:24
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDrawcampCreateRequest extends AbstractAlipayRequest
{
    /**
     * 营销抽奖活动创建
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
