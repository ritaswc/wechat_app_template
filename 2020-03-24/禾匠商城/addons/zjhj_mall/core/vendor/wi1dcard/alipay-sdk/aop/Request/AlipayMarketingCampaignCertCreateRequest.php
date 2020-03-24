<?php
/**
 * ALIPAY API: alipay.marketing.campaign.cert.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-23 14:21:57
 */

namespace Alipay\Request;

class AlipayMarketingCampaignCertCreateRequest extends AbstractAlipayRequest
{
    /**
     * 营销抽奖活动凭证创建
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
