<?php
/**
 * ALIPAY API: alipay.mobile.beacon.device.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-28 11:15:27
 */

namespace Alipay\Request;

class AlipayMobileBeaconDeviceModifyRequest extends AbstractAlipayRequest
{
    /**
     * 设备信息，格式为JSON字符串
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
