<?php
/**
 * ALIPAY API: alipay.pcredit.loan.refund.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-15 19:31:13
 */

namespace Alipay\Request;

class AlipayPcreditLoanRefundCreateRequest extends AbstractAlipayRequest
{
    /**
     * 商户还款
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
