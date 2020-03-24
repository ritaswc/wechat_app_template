<?php
/**
 * ALIPAY API: zhima.customer.ep.certification.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-27 14:28:48
 */

namespace Alipay\Request;

class ZhimaCustomerEpCertificationQueryRequest extends AbstractAlipayRequest
{
    /**
     * 企业认证查询服务
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
