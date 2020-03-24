<?php
/**
 * ALIPAY API: koubei.marketing.campaign.activity.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 16:31:39
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignActivityBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑营销活动列表查询
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
