<?php
/**
 * ALIPAY API: alipay.security.prod.signature.task.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-20 15:24:43
 */

namespace Alipay\Request;

class AlipaySecurityProdSignatureTaskQueryRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝可信电子签名结果查询
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
