<?php
/**
 * ALIPAY API: alipay.commerce.transport.offlinepay.record.verify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-04 17:14:03
 */

namespace Alipay\Request;

class AlipayCommerceTransportOfflinepayRecordVerifyRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝脱机操作信息验证
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
