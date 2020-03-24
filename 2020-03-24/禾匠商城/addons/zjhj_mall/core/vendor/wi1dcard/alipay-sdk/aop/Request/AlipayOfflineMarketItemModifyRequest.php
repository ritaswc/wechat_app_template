<?php
/**
 * ALIPAY API: alipay.offline.market.item.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-20 15:18:01
 */

namespace Alipay\Request;

class AlipayOfflineMarketItemModifyRequest extends AbstractAlipayRequest
{
    /**
     * 商户可以通过此接口对商品进行库存等信息的修改（库存修改值只能大于当前值）
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
