<?php
/**
 * ALIPAY API: alipay.offline.marketing.voucher.status.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-12 10:57:57
 */

namespace Alipay\Request;

class AlipayOfflineMarketingVoucherStatusQueryRequest extends AbstractAlipayRequest
{
    /**
     * 券状态查询
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
