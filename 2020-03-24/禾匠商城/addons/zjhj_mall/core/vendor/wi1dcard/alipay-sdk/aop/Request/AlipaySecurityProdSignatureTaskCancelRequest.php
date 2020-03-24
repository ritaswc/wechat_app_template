<?php
/**
 * ALIPAY API: alipay.security.prod.signature.task.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-20 15:25:04
 */

namespace Alipay\Request;

class AlipaySecurityProdSignatureTaskCancelRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝可信电子签名任务撤销
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
