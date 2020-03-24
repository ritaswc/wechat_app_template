<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.deposit.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2015-12-18 21:35:58
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorDepositCancelRequest extends AbstractAlipayRequest
{
    /**
     * 合作渠道可通过该接口补登扣款取消请求，以帮助支付宝将用户的资金退给用户
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
