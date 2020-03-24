<?php
/**
 * ALIPAY API: alipay.security.prod.fingerprint.riskcontrol.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-28 17:49:30
 */

namespace Alipay\Request;

class AlipaySecurityProdFingerprintRiskcontrolQueryRequest extends AbstractAlipayRequest
{
    /**
     * 指纹风险机型查询
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
