<?php
/**
 * ALIPAY API: alipay.offline.market.shop.discount.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-05 10:50:38
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopDiscountQueryRequest extends AbstractAlipayRequest
{
    /**
     * 基于门店id的优惠查询服务
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
