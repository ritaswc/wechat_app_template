<?php
/**
 * ALIPAY API: alipay.ins.cooperation.region.qrcode.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-24 10:29:59
 */

namespace Alipay\Request;

class AlipayInsCooperationRegionQrcodeApplyRequest extends AbstractAlipayRequest
{
    /**
     * （快捷投保）生成专区二维码
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
