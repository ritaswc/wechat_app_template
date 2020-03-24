<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.voucher.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-03 16:11:01
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorVoucherBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 地铁购票订单批量查询
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
