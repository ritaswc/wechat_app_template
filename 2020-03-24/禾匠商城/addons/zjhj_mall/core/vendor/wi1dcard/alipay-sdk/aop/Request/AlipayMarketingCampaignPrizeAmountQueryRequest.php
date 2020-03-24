<?php
/**
 * ALIPAY API: alipay.marketing.campaign.prize.amount.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-23 14:22:01
 */

namespace Alipay\Request;

class AlipayMarketingCampaignPrizeAmountQueryRequest extends AbstractAlipayRequest
{
    /**
     * 奖品剩余数量查询
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
