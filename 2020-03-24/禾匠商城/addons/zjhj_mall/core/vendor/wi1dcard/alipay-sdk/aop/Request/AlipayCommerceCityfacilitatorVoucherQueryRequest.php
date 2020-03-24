<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.voucher.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-21 15:04:33
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorVoucherQueryRequest extends AbstractAlipayRequest
{
    /**
     * 钱包中地铁票购票，获得核销码，线下地铁自助购票机上凭核销码取票，渠道商凭用户输入的核销码调接口查询核销码的有效性。
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
