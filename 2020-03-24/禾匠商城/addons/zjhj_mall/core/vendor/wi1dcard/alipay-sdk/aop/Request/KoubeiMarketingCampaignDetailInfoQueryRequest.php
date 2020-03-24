<?php
/**
 * ALIPAY API: koubei.marketing.campaign.detail.info.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-10 14:14:30
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignDetailInfoQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询活动的详情信息
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
