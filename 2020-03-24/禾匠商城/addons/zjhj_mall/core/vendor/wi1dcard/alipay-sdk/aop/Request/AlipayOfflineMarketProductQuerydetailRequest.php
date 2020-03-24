<?php
/**
 * ALIPAY API: alipay.offline.market.product.querydetail request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 11:41:52
 */

namespace Alipay\Request;

class AlipayOfflineMarketProductQuerydetailRequest extends AbstractAlipayRequest
{
    /**
     * 通过该接口可以查询商户录入的指定商品详细信息
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
