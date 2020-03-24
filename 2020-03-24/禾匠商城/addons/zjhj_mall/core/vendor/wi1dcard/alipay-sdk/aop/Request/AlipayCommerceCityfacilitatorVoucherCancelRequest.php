<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.voucher.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-21 15:07:46
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorVoucherCancelRequest extends AbstractAlipayRequest
{
    /**
     * 钱包中地铁票购票，获得核销码，线下渠道商凭核销码撤销该笔交易
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
