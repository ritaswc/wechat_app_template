<?php
/**
 * ALIPAY API: koubei.marketing.campaign.crowd.count request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 16:13:57
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignCrowdCountRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商户人群组数目统计接口
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
