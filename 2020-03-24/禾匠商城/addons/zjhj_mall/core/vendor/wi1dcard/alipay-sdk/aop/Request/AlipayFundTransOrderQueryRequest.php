<?php
/**
 * ALIPAY API: alipay.fund.trans.order.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-29 17:23:47
 */

namespace Alipay\Request;

class AlipayFundTransOrderQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询转账订单接口
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
