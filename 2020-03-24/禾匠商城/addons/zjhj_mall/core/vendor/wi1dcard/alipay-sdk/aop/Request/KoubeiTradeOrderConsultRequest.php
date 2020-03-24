<?php
/**
 * ALIPAY API: koubei.trade.order.consult request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-02 10:51:08
 */

namespace Alipay\Request;

class KoubeiTradeOrderConsultRequest extends AbstractAlipayRequest
{
    /**
     * 口碑订单预咨询
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
