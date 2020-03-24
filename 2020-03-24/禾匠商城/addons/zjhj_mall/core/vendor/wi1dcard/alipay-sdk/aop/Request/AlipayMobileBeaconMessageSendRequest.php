<?php
/**
 * ALIPAY API: alipay.mobile.beacon.message.send request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-28 11:13:19
 */

namespace Alipay\Request;

class AlipayMobileBeaconMessageSendRequest extends AbstractAlipayRequest
{
    /**
     * 设备关联数据
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
