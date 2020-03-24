<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.sites.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-26 10:13:20
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieSitesDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除云凤蝶站点
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
