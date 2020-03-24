<?php
/**
 * ALIPAY API: alipay.commerce.iot.deviceservice.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-24 15:20:44
 */

namespace Alipay\Request;

class AlipayCommerceIotDeviceserviceCancelRequest extends AbstractAlipayRequest
{
    /**
     * 撤销指定的设备服务
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
