<?php
/**
 * ALIPAY API: alipay.open.agent.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-31 21:35:35
 */

namespace Alipay\Request;

class AlipayOpenAgentCancelRequest extends AbstractAlipayRequest
{
    /**
     * 取消代商户签约、创建应用事务
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
