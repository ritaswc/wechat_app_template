<?php
/**
 * ALIPAY API: alipay.marketing.voucher.confirm request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-30 22:42:32
 */

namespace Alipay\Request;

class AlipayMarketingVoucherConfirmRequest extends AbstractAlipayRequest
{
    /**
     * 用户确认领券
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
