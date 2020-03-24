<?php
/**
 * ALIPAY API: alipay.open.public.qrcode.create request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:59:38
 */

namespace Alipay\Request;

class AlipayOpenPublicQrcodeCreateRequest extends AbstractAlipayRequest
{
    /**
     * createQrCodeProcessor
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
