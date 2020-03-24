<?php
/**
 * ALIPAY API: alipay.marketing.voucher.stock.use request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-08 16:26:31
 */

namespace Alipay\Request;

class AlipayMarketingVoucherStockUseRequest extends AbstractAlipayRequest
{
    /**
     * 外部商户券码券核销
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
