<?php
/**
 * ALIPAY API: koubei.marketing.campaign.crowd.detail.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:18:27
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignCrowdDetailQueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商户人群组详情查询接口
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
