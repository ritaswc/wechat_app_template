<?php
/**
 * ALIPAY API: koubei.marketing.campaign.user.asset.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-21 15:19:49
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignUserAssetQueryRequest extends AbstractAlipayRequest
{
    /**
     * 用户口碑优惠资产查询接口
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
