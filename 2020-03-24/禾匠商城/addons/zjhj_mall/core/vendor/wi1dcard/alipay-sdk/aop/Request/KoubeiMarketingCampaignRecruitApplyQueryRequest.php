<?php
/**
 * ALIPAY API: koubei.marketing.campaign.recruit.apply.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-20 13:50:48
 */

namespace Alipay\Request;

class KoubeiMarketingCampaignRecruitApplyQueryRequest extends AbstractAlipayRequest
{
    /**
     * 招商商家报名分页查询接口
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
