<?php
/**
 * ALIPAY API: alipay.commerce.air.xfg.dsg.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-18 15:28:48
 */

namespace Alipay\Request;

class AlipayCommerceAirXfgDsgModifyRequest extends AbstractAlipayRequest
{
    /**
     * 吃饭更好
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
