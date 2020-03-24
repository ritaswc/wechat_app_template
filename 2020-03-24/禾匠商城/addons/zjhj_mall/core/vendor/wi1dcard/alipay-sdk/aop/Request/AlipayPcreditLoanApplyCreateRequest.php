<?php
/**
 * ALIPAY API: alipay.pcredit.loan.apply.create request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-25 10:32:50
 */

namespace Alipay\Request;

class AlipayPcreditLoanApplyCreateRequest extends AbstractAlipayRequest
{
    /**
     * 用户申贷
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
