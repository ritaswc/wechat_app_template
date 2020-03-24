<?php
/**
 * ALIPAY API: alipay.marketing.campaign.cash.detail.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-17 12:15:33
 */

namespace Alipay\Request;

class AlipayMarketingCampaignCashDetailQueryRequest extends AbstractAlipayRequest
{
    /**
     * 现金活动详情查询
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
