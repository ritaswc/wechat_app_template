<?php
/**
 * ALIPAY API: koubei.trade.item.buy request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-11 16:39:57
 */

namespace Alipay\Request;

class KoubeiTradeItemBuyRequest extends AbstractAlipayRequest
{
    /**
     * 获取购特车scheme
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
