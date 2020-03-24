<?php
/**
 * ALIPAY API: alipay.offline.marketing.voucher.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-12 10:55:02
 */

namespace Alipay\Request;

class AlipayOfflineMarketingVoucherModifyRequest extends AbstractAlipayRequest
{
    /**
     * 券修改
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
