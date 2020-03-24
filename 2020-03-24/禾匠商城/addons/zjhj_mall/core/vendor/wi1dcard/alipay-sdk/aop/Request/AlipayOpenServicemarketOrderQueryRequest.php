<?php
/**
 * ALIPAY API: alipay.open.servicemarket.order.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-24 23:38:46
 */

namespace Alipay\Request;

class AlipayOpenServicemarketOrderQueryRequest extends AbstractAlipayRequest
{
    /**
     * 用于服务商回查服务市场订单明细信息
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
