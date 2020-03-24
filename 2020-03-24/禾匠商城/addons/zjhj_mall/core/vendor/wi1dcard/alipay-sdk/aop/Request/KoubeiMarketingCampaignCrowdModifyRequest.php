<?php
/**
 * ALIPAY API: koubei.marketing.campaign.crowd.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:20:03
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignCrowdModifyRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商户人群组修改接口
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
