<?php
/**
 * ALIPAY API: alipay.eco.mycar.trade.refund request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:25
 */

namespace Alipay\Request;

class AlipayEcoMycarTradeRefundRequest extends AbstractAlipayRequest
{
    /**
     * 汽车超人退款节接口
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
