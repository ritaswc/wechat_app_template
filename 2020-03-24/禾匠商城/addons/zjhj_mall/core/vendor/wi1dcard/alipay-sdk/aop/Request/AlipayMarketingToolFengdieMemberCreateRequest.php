<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.member.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-17 14:50:00
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieMemberCreateRequest extends AbstractAlipayRequest
{
    /**
     * 创建云凤蝶空间成员
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
