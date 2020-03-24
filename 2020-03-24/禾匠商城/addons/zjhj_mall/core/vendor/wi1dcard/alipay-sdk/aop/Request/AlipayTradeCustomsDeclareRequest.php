<?php
/**
 * ALIPAY API: alipay.trade.customs.declare request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 00:48:24
 */

namespace Alipay\Request;

class AlipayTradeCustomsDeclareRequest extends AbstractAlipayRequest
{
    /**
     * 统一收单报关接口
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
