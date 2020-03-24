<?php
/**
 * ALIPAY API: alipay.marketing.campaign.discount.whitelist.update request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-03 16:47:53
 */

namespace Alipay\Request;

class AlipayMarketingCampaignDiscountWhitelistUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 优惠活动白名单设置
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
