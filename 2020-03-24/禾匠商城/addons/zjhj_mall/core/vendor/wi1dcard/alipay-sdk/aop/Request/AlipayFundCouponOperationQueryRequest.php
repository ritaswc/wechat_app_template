<?php
/**
 * ALIPAY API: alipay.fund.coupon.operation.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-21 16:48:43
 */

namespace Alipay\Request;

class AlipayFundCouponOperationQueryRequest extends AbstractAlipayRequest
{
    /**
     * 资金授权明细查询接口
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
