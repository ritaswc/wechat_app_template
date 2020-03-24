<?php
/**
 * ALIPAY API: alipay.marketing.voucher.stock.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-02 14:46:25
 */

namespace Alipay\Request;

class AlipayMarketingVoucherStockQueryRequest extends AbstractAlipayRequest
{
    /**
     * 外部商户券码券查询
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
