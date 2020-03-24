<?php
/**
 * ALIPAY API: alipay.trade.page.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-04 11:35:00
 */

namespace Alipay\Request;

class AlipayTradePagePayRequest extends AbstractAlipayRequest
{
    /**
     * 统一收单下单并支付页面接口
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
