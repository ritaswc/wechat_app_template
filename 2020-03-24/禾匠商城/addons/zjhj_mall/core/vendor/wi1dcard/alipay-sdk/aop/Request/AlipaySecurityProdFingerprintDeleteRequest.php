<?php
/**
 * ALIPAY API: alipay.security.prod.fingerprint.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-02 14:26:17
 */

namespace Alipay\Request;

class AlipaySecurityProdFingerprintDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 指纹解注册
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
