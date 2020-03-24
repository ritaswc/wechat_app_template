<?php
/**
 * ALIPAY API: alipay.marketing.voucher.auth.send request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-19 11:27:25
 */

namespace Alipay\Request;

class AlipayMarketingVoucherAuthSendRequest extends AbstractAlipayRequest
{
    /**
     * 发送资金券
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
