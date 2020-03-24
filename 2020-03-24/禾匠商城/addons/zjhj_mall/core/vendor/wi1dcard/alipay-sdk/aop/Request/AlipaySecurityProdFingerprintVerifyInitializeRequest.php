<?php
/**
 * ALIPAY API: alipay.security.prod.fingerprint.verify.initialize request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-02 14:25:20
 */

namespace Alipay\Request;

class AlipaySecurityProdFingerprintVerifyInitializeRequest extends AbstractAlipayRequest
{
    /**
     * 指纹校验初始化
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
