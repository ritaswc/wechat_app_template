<?php
/**
 * ALIPAY API: alipay.mobile.beacon.device.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-28 11:14:55
 */

namespace Alipay\Request;

class AlipayMobileBeaconDeviceDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除的设备的UUID
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
