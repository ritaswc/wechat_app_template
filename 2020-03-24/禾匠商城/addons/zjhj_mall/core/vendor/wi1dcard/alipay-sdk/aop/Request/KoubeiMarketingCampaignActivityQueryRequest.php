<?php
/**
 * ALIPAY API: koubei.marketing.campaign.activity.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 16:16:49
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignActivityQueryRequest extends AbstractAlipayRequest
{
    /**
     * 活动详情查询
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
