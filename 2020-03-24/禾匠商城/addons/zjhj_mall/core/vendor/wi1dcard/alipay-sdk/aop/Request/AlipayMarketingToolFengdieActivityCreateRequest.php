<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.activity.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-02 10:31:23
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieActivityCreateRequest extends AbstractAlipayRequest
{
    /**
     * 创建凤蝶活动
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
