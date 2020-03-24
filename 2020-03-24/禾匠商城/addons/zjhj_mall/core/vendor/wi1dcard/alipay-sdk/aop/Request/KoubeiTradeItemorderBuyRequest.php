<?php
/**
 * ALIPAY API: koubei.trade.itemorder.buy request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-07 11:30:00
 */

namespace Alipay\Request;

class KoubeiTradeItemorderBuyRequest extends AbstractAlipayRequest
{
    /**
     * 商品下单购买
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
