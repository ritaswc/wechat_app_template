<?php
/**
 * ALIPAY API: alipay.marketing.exchangevoucher.use request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-30 22:44:13
 */

namespace Alipay\Request;

class AlipayMarketingExchangevoucherUseRequest extends AbstractAlipayRequest
{
    /**
     * 兑换券核销接口
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
