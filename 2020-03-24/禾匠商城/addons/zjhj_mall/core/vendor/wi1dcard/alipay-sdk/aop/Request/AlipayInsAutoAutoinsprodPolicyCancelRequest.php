<?php
/**
 * ALIPAY API: alipay.ins.auto.autoinsprod.policy.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-13 11:31:43
 */

namespace Alipay\Request;

class AlipayInsAutoAutoinsprodPolicyCancelRequest extends AbstractAlipayRequest
{
    /**
     * 下单撤销接口
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
