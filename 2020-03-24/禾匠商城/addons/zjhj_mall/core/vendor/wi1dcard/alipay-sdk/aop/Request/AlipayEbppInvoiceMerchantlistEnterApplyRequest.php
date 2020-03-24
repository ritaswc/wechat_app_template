<?php
/**
 * ALIPAY API: alipay.ebpp.invoice.merchantlist.enter.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-02 10:31:06
 */

namespace Alipay\Request;

class AlipayEbppInvoiceMerchantlistEnterApplyRequest extends AbstractAlipayRequest
{
    /**
     * 商户批量入驻申请接口
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
