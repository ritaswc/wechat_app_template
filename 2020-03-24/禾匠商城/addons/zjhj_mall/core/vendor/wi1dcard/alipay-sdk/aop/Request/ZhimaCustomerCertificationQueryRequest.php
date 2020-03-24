<?php
/**
 * ALIPAY API: zhima.customer.certification.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-19 13:55:11
 */

namespace Alipay\Request;

class ZhimaCustomerCertificationQueryRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻认证查询
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
