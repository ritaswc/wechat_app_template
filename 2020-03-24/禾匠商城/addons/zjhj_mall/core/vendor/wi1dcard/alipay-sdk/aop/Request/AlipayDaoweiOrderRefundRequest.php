<?php
/**
 * ALIPAY API: alipay.daowei.order.refund request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 13:22:08
 */

namespace Alipay\Request;

class AlipayDaoweiOrderRefundRequest extends AbstractAlipayRequest
{
    /**
     * 订单退款接口
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
