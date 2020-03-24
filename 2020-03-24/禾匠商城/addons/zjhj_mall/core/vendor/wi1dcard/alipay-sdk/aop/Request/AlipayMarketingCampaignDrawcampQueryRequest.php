<?php
/**
 * ALIPAY API: alipay.marketing.campaign.drawcamp.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-23 14:22:15
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDrawcampQueryRequest extends AbstractAlipayRequest
{
    /**
     * 营销抽奖活动查询
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
