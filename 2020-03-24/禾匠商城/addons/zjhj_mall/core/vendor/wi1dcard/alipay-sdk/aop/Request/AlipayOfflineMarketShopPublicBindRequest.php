<?php
/**
 * ALIPAY API: alipay.offline.market.shop.public.bind request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-29 19:57:30
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopPublicBindRequest extends AbstractAlipayRequest
{
    /**
     * 是否绑定所有门店，T表示绑定所有门店，F表示绑定指定shop_ids的门店
     **/
    private $isAll;
    /**
     * 门店ID列表，一次最多绑定500个门店，is_all为T时表示绑定本商家下所有门店，即门店列表无需通过本参数shop_ids传入，由系统自动查询;is_all为F时该参数为必填
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
