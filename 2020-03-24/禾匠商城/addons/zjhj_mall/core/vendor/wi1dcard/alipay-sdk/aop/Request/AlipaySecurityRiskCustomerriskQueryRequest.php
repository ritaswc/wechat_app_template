<?php
/**
 * ALIPAY API: alipay.security.risk.customerrisk.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-03 17:49:14
 */

namespace Alipay\Request;

class AlipaySecurityRiskCustomerriskQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户风险服务输出
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
