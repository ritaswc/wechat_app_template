<?php
/**
 * ALIPAY API: alipay.security.prod.alipay.security.prod.test request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-03 17:43:31
 */

namespace Alipay\Request;

class AlipaySecurityProdAlipaySecurityProdTestRequest extends AbstractAlipayRequest
{
    /**
     * 安全业务操作
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
