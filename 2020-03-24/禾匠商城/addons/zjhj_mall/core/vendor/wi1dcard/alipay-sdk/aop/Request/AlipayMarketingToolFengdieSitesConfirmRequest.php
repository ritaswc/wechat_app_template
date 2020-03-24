<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.sites.confirm request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-26 10:12:39
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieSitesConfirmRequest extends AbstractAlipayRequest
{
    /**
     * 云凤蝶站点发布审批
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
