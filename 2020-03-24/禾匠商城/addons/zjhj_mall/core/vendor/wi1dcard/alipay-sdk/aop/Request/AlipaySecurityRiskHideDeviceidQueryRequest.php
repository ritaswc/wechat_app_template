<?php
/**
 * ALIPAY API: alipay.security.risk.hide.deviceid.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-17 15:05:08
 */

namespace Alipay\Request;

class AlipaySecurityRiskHideDeviceidQueryRequest extends AbstractAlipayRequest
{
    /**
     * 设备指纹查询接口
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
