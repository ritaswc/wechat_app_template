<?php
/**
 * ALIPAY API: koubei.marketing.campaign.crowd.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-15 16:30:54
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignCrowdBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商户人群组列表查询接口
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
