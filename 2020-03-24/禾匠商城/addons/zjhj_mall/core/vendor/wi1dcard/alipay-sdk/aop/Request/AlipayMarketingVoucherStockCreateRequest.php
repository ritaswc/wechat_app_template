<?php
/**
 * ALIPAY API: alipay.marketing.voucher.stock.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-02 14:47:38
 */

namespace Alipay\Request;

class AlipayMarketingVoucherStockCreateRequest extends AbstractAlipayRequest
{
    /**
     * 外部商户券码券上传
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
