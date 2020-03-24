<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.space.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-26 10:13:38
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieSpaceCreateRequest extends AbstractAlipayRequest
{
    /**
     * 创建空间
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
