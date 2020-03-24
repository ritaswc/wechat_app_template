<?php
/**
 * ALIPAY API: alipay.open.servicemarket.order.accept request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:47:51
 */

namespace Alipay\Request;

class AlipayOpenServicemarketOrderAcceptRequest extends AbstractAlipayRequest
{
    /**
     * 服务商接单操作
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
