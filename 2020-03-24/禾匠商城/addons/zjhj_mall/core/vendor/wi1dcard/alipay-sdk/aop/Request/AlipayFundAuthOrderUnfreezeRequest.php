<?php
/**
 * ALIPAY API: alipay.fund.auth.order.unfreeze request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-21 12:18:28
 */

namespace Alipay\Request;

class AlipayFundAuthOrderUnfreezeRequest extends AbstractAlipayRequest
{
    /**
     * 预授权资金解冻接口
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
