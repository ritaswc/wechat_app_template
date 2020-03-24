<?php
/**
 * ALIPAY API: alipay.open.agent.order.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-31 21:39:01
 */

namespace Alipay\Request;

class AlipayOpenAgentOrderQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询申请单状态
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
