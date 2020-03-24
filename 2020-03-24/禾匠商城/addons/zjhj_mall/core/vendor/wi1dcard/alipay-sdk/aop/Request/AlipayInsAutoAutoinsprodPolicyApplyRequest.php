<?php
/**
 * ALIPAY API: alipay.ins.auto.autoinsprod.policy.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-09 11:49:42
 */

namespace Alipay\Request;

class AlipayInsAutoAutoinsprodPolicyApplyRequest extends AbstractAlipayRequest
{
    /**
     * 下单请求接口
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
