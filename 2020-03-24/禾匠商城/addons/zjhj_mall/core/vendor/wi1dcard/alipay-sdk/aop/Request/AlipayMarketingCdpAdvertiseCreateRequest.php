<?php
/**
 * ALIPAY API: alipay.marketing.cdp.advertise.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-29 12:01:26
 */

namespace Alipay\Request;

class AlipayMarketingCdpAdvertiseCreateRequest extends AbstractAlipayRequest
{
    /**
     * 提供给ISV、开发者创建广告的接口，创建广告后投放渠道包括钱包APP，聚牛APP等，投放支持的APP应用
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
