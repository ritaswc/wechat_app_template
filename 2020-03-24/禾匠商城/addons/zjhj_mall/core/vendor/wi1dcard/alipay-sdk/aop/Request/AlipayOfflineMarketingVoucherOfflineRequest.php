<?php
/**
 * ALIPAY API: alipay.offline.marketing.voucher.offline request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-12 10:56:10
 */

namespace Alipay\Request;

class AlipayOfflineMarketingVoucherOfflineRequest extends AbstractAlipayRequest
{
    /**
     * 券下架
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
