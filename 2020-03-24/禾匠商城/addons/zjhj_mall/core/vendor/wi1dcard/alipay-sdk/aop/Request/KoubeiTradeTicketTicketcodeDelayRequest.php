<?php
/**
 * ALIPAY API: koubei.trade.ticket.ticketcode.delay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-21 11:33:24
 */

namespace Alipay\Request;

class KoubeiTradeTicketTicketcodeDelayRequest extends AbstractAlipayRequest
{
    /**
     * 口碑凭证延期接口
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
