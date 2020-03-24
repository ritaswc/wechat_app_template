<?php
/**
 * ALIPAY API: koubei.trade.ticket.ticketcode.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-02 14:41:39
 */

namespace Alipay\Request;

class KoubeiTradeTicketTicketcodeSendRequest extends AbstractAlipayRequest
{
    /**
     * 码商发码成功回调接口
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
