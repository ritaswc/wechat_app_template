<?php
/**
 * ALIPAY API: alipay.open.servicemarket.order.reject request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-25 11:11:47
 */

namespace Alipay\Request;

class AlipayOpenServicemarketOrderRejectRequest extends AbstractAlipayRequest
{
    /**
     * 服务商拒绝接单
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
