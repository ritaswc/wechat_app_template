<?php
/**
 * ALIPAY API: koubei.marketing.campaign.crowd.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:16:38
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignCrowdCreateRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商户人群组创建接口
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
