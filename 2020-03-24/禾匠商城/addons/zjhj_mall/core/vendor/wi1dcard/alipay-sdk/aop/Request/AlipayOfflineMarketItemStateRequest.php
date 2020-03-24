<?php
/**
 * ALIPAY API: alipay.offline.market.item.state request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-20 15:17:50
 */

namespace Alipay\Request;

class AlipayOfflineMarketItemStateRequest extends AbstractAlipayRequest
{
    /**
     * 通过此接口，商户可以出传入item_id与上下架标识，对商户创建的商品进行上架或下架处理
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
