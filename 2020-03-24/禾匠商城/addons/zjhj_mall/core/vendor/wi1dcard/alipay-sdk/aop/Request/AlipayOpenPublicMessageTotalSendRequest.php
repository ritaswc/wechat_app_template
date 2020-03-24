<?php
/**
 * ALIPAY API: alipay.open.public.message.total.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-22 10:33:37
 */

namespace Alipay\Request;

class AlipayOpenPublicMessageTotalSendRequest extends AbstractAlipayRequest
{
    /**
     * alipay.open.public. message.total.send（群发消息）
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
