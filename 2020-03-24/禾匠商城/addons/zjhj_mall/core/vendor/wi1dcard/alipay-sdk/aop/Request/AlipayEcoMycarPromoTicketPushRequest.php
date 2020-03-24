<?php
/**
 * ALIPAY API: alipay.eco.mycar.promo.ticket.push request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:58
 */

namespace Alipay\Request;

class AlipayEcoMycarPromoTicketPushRequest extends AbstractAlipayRequest
{
    /**
     * 车主营销平台券核销结果通知
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
