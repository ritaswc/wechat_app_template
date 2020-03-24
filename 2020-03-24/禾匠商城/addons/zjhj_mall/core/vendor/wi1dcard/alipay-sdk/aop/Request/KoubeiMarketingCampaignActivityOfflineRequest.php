<?php
/**
 * ALIPAY API: koubei.marketing.campaign.activity.offline request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 20:12:57
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignActivityOfflineRequest extends AbstractAlipayRequest
{
    /**
     * 活动下架接口
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
