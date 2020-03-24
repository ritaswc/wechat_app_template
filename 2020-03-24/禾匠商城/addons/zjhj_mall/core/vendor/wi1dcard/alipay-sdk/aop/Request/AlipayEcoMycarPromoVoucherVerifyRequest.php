<?php
/**
 * ALIPAY API: alipay.eco.mycar.promo.voucher.verify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:31
 */

namespace Alipay\Request;

class AlipayEcoMycarPromoVoucherVerifyRequest extends AbstractAlipayRequest
{
    /**
     * 核销
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
