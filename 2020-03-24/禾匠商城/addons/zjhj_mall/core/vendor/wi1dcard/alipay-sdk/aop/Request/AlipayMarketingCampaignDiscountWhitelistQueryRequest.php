<?php
/**
 * ALIPAY API: alipay.marketing.campaign.discount.whitelist.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-03 16:48:04
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDiscountWhitelistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 优惠活动白名单查询
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
