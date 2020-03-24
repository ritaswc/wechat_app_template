<?php
/**
 * ALIPAY API: alipay.open.public.third.customer.service request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 12:11:15
 */

namespace Alipay\Request;

class AlipayOpenPublicThirdCustomerServiceRequest extends AbstractAlipayRequest
{
    /**
     * 服务窗第三方渠道商配置接口，用于记录服务窗商户授权的第三方渠道商
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
