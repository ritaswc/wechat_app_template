<?php
/**
 * ALIPAY API: alipay.ebpp.invoice.apply.result.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-21 14:45:00
 */

namespace Alipay\Request;

class AlipayEbppInvoiceApplyResultSyncRequest extends AbstractAlipayRequest
{
    /**
     * 同步发票申请结果
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
