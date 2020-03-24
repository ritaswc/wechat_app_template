<?php
/**
 * ALIPAY API: alipay.offline.market.applyorder.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-27 17:07:28
 */

namespace Alipay\Request;

class AlipayOfflineMarketApplyorderBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 分页查询Leads、门店、商品相关操作流水信息
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
