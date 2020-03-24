<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.space.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-26 10:14:41
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieSpaceQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询云凤蝶空间详情
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
