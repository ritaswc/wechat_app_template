<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.voucher.generate request
 *
 * @author auto create
 *
 * @since  1.0, 2016-08-03 16:10:34
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorVoucherGenerateRequest extends AbstractAlipayRequest
{
    /**
     * 地铁购票核销码发码
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
