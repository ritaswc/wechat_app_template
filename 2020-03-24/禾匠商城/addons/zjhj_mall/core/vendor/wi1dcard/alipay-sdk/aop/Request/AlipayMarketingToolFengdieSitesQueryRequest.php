<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.sites.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-25 16:45:00
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieSitesQueryRequest extends AbstractAlipayRequest
{
    /**
     * 获取云凤蝶站点详情
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
