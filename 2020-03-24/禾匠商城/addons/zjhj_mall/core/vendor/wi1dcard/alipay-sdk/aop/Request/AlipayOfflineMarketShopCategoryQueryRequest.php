<?php
/**
 * ALIPAY API: alipay.offline.market.shop.category.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-19 16:56:09
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopCategoryQueryRequest extends AbstractAlipayRequest
{
    /**
     * 门店类目配置查询接口
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
