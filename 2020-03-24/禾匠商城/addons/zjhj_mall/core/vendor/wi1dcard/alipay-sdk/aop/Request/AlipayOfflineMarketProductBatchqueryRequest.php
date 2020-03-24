<?php
/**
 * ALIPAY API: alipay.offline.market.product.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 11:43:44
 */

namespace Alipay\Request;

class AlipayOfflineMarketProductBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 通过该接口可以查询商户录入的所有商品编号
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
