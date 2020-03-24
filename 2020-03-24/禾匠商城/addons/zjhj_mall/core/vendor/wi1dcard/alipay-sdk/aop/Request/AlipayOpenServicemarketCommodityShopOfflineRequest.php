<?php
/**
 * ALIPAY API: alipay.open.servicemarket.commodity.shop.offline request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-25 11:11:17
 */

namespace Alipay\Request;

class AlipayOpenServicemarketCommodityShopOfflineRequest extends AbstractAlipayRequest
{
    /**
     * 下架商户门店
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
