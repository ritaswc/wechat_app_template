<?php
/**
 * ALIPAY API: koubei.trade.order.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-09 11:11:08
 */

namespace Alipay\Request;

class KoubeiTradeOrderQueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑订单详情
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
