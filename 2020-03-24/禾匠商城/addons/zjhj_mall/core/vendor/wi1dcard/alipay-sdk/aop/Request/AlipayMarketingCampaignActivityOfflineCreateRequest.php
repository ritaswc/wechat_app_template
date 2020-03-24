<?php
/**
 * ALIPAY API: alipay.marketing.campaign.activity.offline.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-07 18:22:19
 */

namespace Alipay\Request;

class AlipayMarketingCampaignActivityOfflineCreateRequest extends AbstractAlipayRequest
{
    /**
     * 能够创商户建领券活动,后续支持
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
