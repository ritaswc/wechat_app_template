<?php
/**
 * ALIPAY API: alipay.daowei.order.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 13:21:44
 */

namespace Alipay\Request;

class AlipayDaoweiOrderCancelRequest extends AbstractAlipayRequest
{
    /**
     * 到位订单取消
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
