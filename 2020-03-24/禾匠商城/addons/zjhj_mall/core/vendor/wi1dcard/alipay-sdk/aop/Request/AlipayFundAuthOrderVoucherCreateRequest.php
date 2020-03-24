<?php
/**
 * ALIPAY API: alipay.fund.auth.order.voucher.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-20 22:10:58
 */

namespace Alipay\Request;

class AlipayFundAuthOrderVoucherCreateRequest extends AbstractAlipayRequest
{
    /**
     * 资金授权发码接口
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
