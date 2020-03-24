<?php
/**
 * ALIPAY API: alipay.security.prod.signature.task.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-20 15:24:35
 */

namespace Alipay\Request;

class AlipaySecurityProdSignatureTaskApplyRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝可信电子签名申请
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
