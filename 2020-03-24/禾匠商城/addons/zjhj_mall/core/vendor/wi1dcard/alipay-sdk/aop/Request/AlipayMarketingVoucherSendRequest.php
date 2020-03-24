<?php
/**
 * ALIPAY API: alipay.marketing.voucher.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-20 18:11:01
 */

namespace Alipay\Request;

class AlipayMarketingVoucherSendRequest extends AbstractAlipayRequest
{
    /**
     * 发券接口
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
