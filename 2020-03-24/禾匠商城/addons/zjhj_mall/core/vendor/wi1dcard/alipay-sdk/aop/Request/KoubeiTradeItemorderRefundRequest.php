<?php
/**
 * ALIPAY API: koubei.trade.itemorder.refund request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-08 13:54:45
 */

namespace Alipay\Request;

class KoubeiTradeItemorderRefundRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商品交易退货接口
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
