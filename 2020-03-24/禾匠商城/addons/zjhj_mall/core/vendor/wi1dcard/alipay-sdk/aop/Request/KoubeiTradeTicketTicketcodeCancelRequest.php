<?php
/**
 * ALIPAY API: koubei.trade.ticket.ticketcode.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-07 16:00:00
 */

namespace Alipay\Request;

class KoubeiTradeTicketTicketcodeCancelRequest extends AbstractAlipayRequest
{
    /**
     * 口碑凭证码撤销核销
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
