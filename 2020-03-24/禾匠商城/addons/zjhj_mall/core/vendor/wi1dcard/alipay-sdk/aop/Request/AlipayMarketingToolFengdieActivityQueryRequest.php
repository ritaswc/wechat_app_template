<?php
/**
 * ALIPAY API: alipay.marketing.tool.fengdie.activity.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-20 10:29:11
 */

namespace Alipay\Request;

class AlipayMarketingToolFengdieActivityQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询凤蝶活动详情
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
