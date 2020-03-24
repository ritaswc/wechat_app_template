<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.deposit.confirm request
 *
 * @author auto create
 *
 * @since  1.0, 2015-12-18 21:36:24
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorDepositConfirmRequest extends AbstractAlipayRequest
{
    /**
     * 合作渠道可通过该接口补登单笔圈存确认扣款请求，以帮助支付宝将用户的资金结算给指定的渠道，不支持单笔拆分
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
