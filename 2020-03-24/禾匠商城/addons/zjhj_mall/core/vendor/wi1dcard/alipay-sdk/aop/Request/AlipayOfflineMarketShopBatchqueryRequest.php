<?php
/**
 * ALIPAY API: alipay.offline.market.shop.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-19 16:55:51
 */

namespace Alipay\Request;

class AlipayOfflineMarketShopBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 系统商通过该接口可以查询所有门店的外部门店编号（系统商的门店编号）
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
