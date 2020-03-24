<?php
/**
 * ALIPAY API: alipay.trade.vendorpay.devicedata.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 00:51:39
 */

namespace Alipay\Request;

class AlipayTradeVendorpayDevicedataUploadRequest extends AbstractAlipayRequest
{
    /**
     * 厂商支付授权时上传设备数据接口，目前主要包含三星支付。com
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
