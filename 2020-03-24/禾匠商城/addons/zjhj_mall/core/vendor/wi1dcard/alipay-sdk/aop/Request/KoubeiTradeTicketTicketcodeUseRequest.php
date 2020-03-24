<?php
/**
 * ALIPAY API: koubei.trade.ticket.ticketcode.use request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-15 14:50:00
 */

namespace Alipay\Request;

class KoubeiTradeTicketTicketcodeUseRequest extends AbstractAlipayRequest
{
    /**
     * 口碑凭证码核销
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
