<?php
/**
 * ALIPAY API: alipay.marketing.voucher.stock.match request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-02 14:45:26
 */

namespace Alipay\Request;

class AlipayMarketingVoucherStockMatchRequest extends AbstractAlipayRequest
{
    /**
     * 外部商户券码券核查
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
