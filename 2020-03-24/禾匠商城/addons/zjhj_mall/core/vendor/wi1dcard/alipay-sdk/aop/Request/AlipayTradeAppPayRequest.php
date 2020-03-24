<?php
/**
 * ALIPAY API: alipay.trade.app.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 19:15:47
 */

namespace Alipay\Request;

class AlipayTradeAppPayRequest extends AbstractAlipayRequest
{
    /**
     * app支付接口2.0
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
