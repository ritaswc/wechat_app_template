<?php
/**
 * ALIPAY API: alipay.marketing.cdp.advertise.operate request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-15 11:52:00
 */

namespace Alipay\Request;

class AlipayMarketingCdpAdvertiseOperateRequest extends AbstractAlipayRequest
{
    /**
     * 提供给ISV、开发者操作广告的接口
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
