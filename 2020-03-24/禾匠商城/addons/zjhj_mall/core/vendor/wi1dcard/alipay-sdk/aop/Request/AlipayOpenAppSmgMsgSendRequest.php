<?php
/**
 * ALIPAY API: alipay.open.app.smg.msg.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-14 14:19:47
 */

namespace Alipay\Request;

class AlipayOpenAppSmgMsgSendRequest extends AbstractAlipayRequest
{
    /**
     * to蚂蚁消息接口
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
