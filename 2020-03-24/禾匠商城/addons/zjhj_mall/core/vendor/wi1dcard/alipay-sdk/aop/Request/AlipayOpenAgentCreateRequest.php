<?php
/**
 * ALIPAY API: alipay.open.agent.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-15 11:25:22
 */

namespace Alipay\Request;

class AlipayOpenAgentCreateRequest extends AbstractAlipayRequest
{
    /**
     * 开启带商户签约、创建应用事务
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
