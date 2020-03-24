<?php
/**
 * ALIPAY API: alipay.pass.verify.query request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-12 17:16:02
 */

namespace Alipay\Request;

class AlipayPassVerifyQueryRequest extends AbstractAlipayRequest
{
    /**
     * Alipass对应的核销码串
     **/
    private $verifyCode;

    public function setVerifyCode($verifyCode)
    {
        $this->verifyCode = $verifyCode;
        $this->apiParams['verify_code'] = $verifyCode;
    }

    public function getVerifyCode()
    {
        return $this->verifyCode;
    }
}
