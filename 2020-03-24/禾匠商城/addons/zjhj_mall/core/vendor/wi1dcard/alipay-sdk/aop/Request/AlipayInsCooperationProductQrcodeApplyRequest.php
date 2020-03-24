<?php
/**
 * ALIPAY API: alipay.ins.cooperation.product.qrcode.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-24 10:29:16
 */

namespace Alipay\Request;

class AlipayInsCooperationProductQrcodeApplyRequest extends AbstractAlipayRequest
{
    /**
     * （快捷投保）生成产品二维码
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
