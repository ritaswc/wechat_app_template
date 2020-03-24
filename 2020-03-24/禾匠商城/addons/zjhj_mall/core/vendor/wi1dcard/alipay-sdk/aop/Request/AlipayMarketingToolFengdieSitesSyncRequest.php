<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.sites.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-26 10:14:05
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieSitesSyncRequest extends AbstractAlipayRequest
{
    /**
     * 升级云凤蝶站点
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
