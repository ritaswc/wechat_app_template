<?php
/**
 * ALIPAY API: zhima.customer.certification.material.certify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-19 19:12:11
 */

namespace Alipay\Request;

class ZhimaCustomerCertificationMaterialCertifyRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻认证材料认证
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
