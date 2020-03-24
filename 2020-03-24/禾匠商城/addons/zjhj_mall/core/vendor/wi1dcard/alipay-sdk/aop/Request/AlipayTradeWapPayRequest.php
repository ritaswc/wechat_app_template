<?php
/**
 * ALIPAY API: alipay.trade.wap.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-08 17:55:00
 */

namespace Alipay\Request;

class AlipayTradeWapPayRequest extends AbstractAlipayRequest
{
    /**
     * 手机网站支付接口2.0
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
