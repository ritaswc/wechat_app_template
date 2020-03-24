<?php
/**
 * ALIPAY API: alipay.trade.refund request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-20 17:20:00
 */

namespace Alipay\Request;

class AlipayTradeRefundRequest extends AbstractAlipayRequest
{
    /**
     * 统一收单交易退款接口
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
