<?php
/**
 * ALIPAY API: alipay.trade.fastpay.refund.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-20 17:15:31
 */

namespace Alipay\Request;

class AlipayTradeFastpayRefundQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户可使用该接口查询自已通过alipay.trade.refund提交的退款请求是否执行成功。
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
