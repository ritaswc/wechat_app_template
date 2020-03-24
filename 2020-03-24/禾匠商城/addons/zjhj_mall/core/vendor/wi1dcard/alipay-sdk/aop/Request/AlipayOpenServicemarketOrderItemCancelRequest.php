<?php
/**
 * ALIPAY API: alipay.open.servicemarket.order.item.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-25 11:11:54
 */

namespace Alipay\Request;

class AlipayOpenServicemarketOrderItemCancelRequest extends AbstractAlipayRequest
{
    /**
     * 服务订单明细实施项单项取消
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
