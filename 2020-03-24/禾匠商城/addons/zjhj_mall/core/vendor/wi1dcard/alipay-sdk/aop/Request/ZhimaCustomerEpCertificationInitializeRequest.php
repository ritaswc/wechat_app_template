<?php
/**
 * ALIPAY API: zhima.customer.ep.certification.initialize request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-27 14:28:16
 */

namespace Alipay\Request;

class ZhimaCustomerEpCertificationInitializeRequest extends AbstractAlipayRequest
{
    /**
     * 企业认证初始化
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
