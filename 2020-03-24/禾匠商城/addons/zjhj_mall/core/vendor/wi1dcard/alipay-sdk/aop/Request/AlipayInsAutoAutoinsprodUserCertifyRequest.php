<?php
/**
 * ALIPAY API: alipay.ins.auto.autoinsprod.user.certify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-13 11:31:13
 */

namespace Alipay\Request;

class AlipayInsAutoAutoinsprodUserCertifyRequest extends AbstractAlipayRequest
{
    /**
     * 代理人实名认证接口
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
