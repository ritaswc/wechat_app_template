<?php
/**
 * ALIPAY API: alipay.fund.auth.order.app.freeze request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-20 22:11:06
 */

namespace Alipay\Request;

class AlipayFundAuthOrderAppFreezeRequest extends AbstractAlipayRequest
{
    /**
     * 线上资金授权冻结接口
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
