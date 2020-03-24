<?php
/**
 * ALIPAY API: alipay.security.prod.amlrisk.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-02-02 15:48:33
 */

namespace Alipay\Request;

class AlipaySecurityProdAmlriskQueryRequest extends AbstractAlipayRequest
{
    /**
     * 该API用于外部商户准入时的反洗钱风险分析。
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
