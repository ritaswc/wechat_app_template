<?php
/**
 * ALIPAY API: alipay.trade.order.settle request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 00:49:25
 */

namespace Alipay\Request;

class AlipayTradeOrderSettleRequest extends AbstractAlipayRequest
{
    /**
     * 统一收单交易结算接口
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
