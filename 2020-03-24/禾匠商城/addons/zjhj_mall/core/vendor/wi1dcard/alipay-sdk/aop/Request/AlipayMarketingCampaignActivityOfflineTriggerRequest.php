<?php
/**
 * ALIPAY API: alipay.marketing.campaign.activity.offline.trigger request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-24 11:22:59
 */

namespace Alipay\Request;

class AlipayMarketingCampaignActivityOfflineTriggerRequest extends AbstractAlipayRequest
{
    /**
     * 商户创建活动后，需营销核心平台，来发奖。
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
