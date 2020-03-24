<?php
/**
 * ALIPAY API: alipay.eco.mycar.promo.ticket.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:40
 */

namespace Alipay\Request;

class AlipayEcoMycarPromoTicketSyncRequest extends AbstractAlipayRequest
{
    /**
     * ISV有新的卡券信息同步到车主服务平台
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
