<?php
/**
 * ALIPAY API: alipay.mobile.beacon.device.add request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-28 11:14:28
 */

namespace Alipay\Request;

class AlipayMobileBeaconDeviceAddRequest extends AbstractAlipayRequest
{
    /**
     * 蓝牙设备信息
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
