<?php
/**
 * ALIPAY API: zhima.customer.certification.initialize request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-19 19:11:31
 */

namespace Alipay\Request;

class ZhimaCustomerCertificationInitializeRequest extends AbstractAlipayRequest
{
    /**
     * 认证初始化
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
