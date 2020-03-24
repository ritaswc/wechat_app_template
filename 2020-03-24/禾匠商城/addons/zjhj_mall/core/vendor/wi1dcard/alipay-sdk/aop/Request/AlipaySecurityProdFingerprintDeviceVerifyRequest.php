<?php
/**
 * ALIPAY API: alipay.security.prod.fingerprint.device.verify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-08 11:20:00
 */

namespace Alipay\Request;

class AlipaySecurityProdFingerprintDeviceVerifyRequest extends AbstractAlipayRequest
{
    /**
     * 指纹注册设备验签
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
