<?php
/**
 * ALIPAY API: zhima.customer.ep.certification.certify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-23 19:13:17
 */

namespace Alipay\Request;

class ZhimaCustomerEpCertificationCertifyRequest extends AbstractAlipayRequest
{
    /**
     * 企业认证引导(页面接口)
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
