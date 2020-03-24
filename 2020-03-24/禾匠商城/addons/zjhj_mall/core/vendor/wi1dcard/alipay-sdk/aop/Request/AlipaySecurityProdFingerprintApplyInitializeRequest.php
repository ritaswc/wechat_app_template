<?php
/**
 * ALIPAY API: alipay.security.prod.fingerprint.apply.initialize request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-02 14:25:02
 */

namespace Alipay\Request;

class AlipaySecurityProdFingerprintApplyInitializeRequest extends AbstractAlipayRequest
{
    /**
     * 指纹注册初始化
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
