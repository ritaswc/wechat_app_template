<?php
/**
 * ALIPAY API: koubei.marketing.campaign.recruit.shop.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 20:08:13
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignRecruitShopQueryRequest extends AbstractAlipayRequest
{
    /**
     * 招商门店分页查询接口
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
