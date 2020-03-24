<?php
/**
 * ALIPAY API: alipay.marketing.cdp.advertise.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-15 11:50:40
 */

namespace Alipay\Request;

class AlipayMarketingCdpAdvertiseQueryRequest extends AbstractAlipayRequest
{
    /**
     * 提供给ISV和开发者查询广告的接口
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
