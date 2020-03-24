<?php
/**
 * ALIPAY API: koubei.marketing.campaign.activity.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-18 14:55:00
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignActivityModifyRequest extends AbstractAlipayRequest
{
    /**
     * 活动修改接口
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
