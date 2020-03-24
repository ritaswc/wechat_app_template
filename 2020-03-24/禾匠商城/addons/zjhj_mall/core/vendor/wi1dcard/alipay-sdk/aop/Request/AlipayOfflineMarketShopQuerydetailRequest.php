<?php
/**
 * ALIPAY API: alipay.offline.market.shop.querydetail request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-19 16:55:57
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopQuerydetailRequest extends AbstractAlipayRequest
{
    /**
     * 系统商通过该接口可以查询单个门店的详细信息，包括门店基础信息，门店状态等信息
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
