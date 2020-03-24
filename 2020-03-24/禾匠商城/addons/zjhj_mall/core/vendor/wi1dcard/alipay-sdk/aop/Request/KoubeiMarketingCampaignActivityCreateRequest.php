<?php
/**
 * ALIPAY API: koubei.marketing.campaign.activity.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-07 16:45:31
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignActivityCreateRequest extends AbstractAlipayRequest
{
    /**
     * 活动创建接口
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
