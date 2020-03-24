<?php
/**
 * ALIPAY API: alipay.open.servicemarket.order.item.complete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-24 23:31:48
 */

namespace Alipay\Request;

class AlipayOpenServicemarketOrderItemCompleteRequest extends AbstractAlipayRequest
{
    /**
     * 服务商完成订单内单个明细实施操作
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
