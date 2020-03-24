<?php
/**
 * ALIPAY API: alipay.security.risk.customerrisk.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-15 13:45:00
 */

namespace Alipay\Request;

class AlipaySecurityRiskCustomerriskSendRequest extends AbstractAlipayRequest
{
    /**
     * 商户数据同步
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
