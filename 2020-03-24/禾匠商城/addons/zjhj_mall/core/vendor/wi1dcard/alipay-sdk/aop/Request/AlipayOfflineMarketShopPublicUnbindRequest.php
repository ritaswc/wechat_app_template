<?php
/**
 * ALIPAY API: alipay.offline.market.shop.public.unbind request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-19 20:52:06
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopPublicUnbindRequest extends AbstractAlipayRequest
{
    /**
     * 是否解绑所有门店，T表示解绑所有门店，F表示解绑指定shop_ids的门店列表
     **/
    private $isAll;
    /**
     * 解除绑定门店的ID列表，一次最多解绑100个门店，is_all为T时表示解除绑定本商家下所有门店，即门店列表无需通过本参数shop_ids传入，由系统自动查询;is_all为F时该参数必填
     **/
    private $shopIds;

    public function setIsAll($isAll)
    {
        $this->isAll = $isAll;
        $this->apiParams['is_all'] = $isAll;
    }

    public function getIsAll()
    {
        return $this->isAll;
    }

    public function setShopIds($shopIds)
    {
        $this->shopIds = $shopIds;
        $this->apiParams['shop_ids'] = $shopIds;
    }

    public function getShopIds()
    {
        return $this->shopIds;
    }
}
