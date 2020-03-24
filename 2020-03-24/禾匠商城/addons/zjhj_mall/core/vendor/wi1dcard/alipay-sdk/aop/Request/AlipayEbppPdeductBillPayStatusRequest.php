<?php
/**
 * ALIPAY API: alipay.ebpp.pdeduct.bill.pay.status request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-04 11:19:05
 */

namespace Alipay\Request;

class AlipayEbppPdeductBillPayStatusRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝用户ID
     **/
    private $agreementId;
    /**
     * 商户代扣业务流水
     **/
    private $outOrderNo;

    public function setAgreementId($agreementId)
    {
        $this->agreementId = $agreementId;
        $this->apiParams['agreement_id'] = $agreementId;
    }

    public function getAgreementId()
    {
        return $this->agreementId;
    }

    public function setOutOrderNo($outOrderNo)
    {
        $this->outOrderNo = $outOrderNo;
        $this->apiParams['out_order_no'] = $outOrderNo;
    }

    public function getOutOrderNo()
    {
        return $this->outOrderNo;
    }
}
