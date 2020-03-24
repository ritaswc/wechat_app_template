<?php
/**
 * ALIPAY API: alipay.open.app.mini.templatemessage.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-06 13:50:53
 */

namespace Alipay\Request;

class AlipayOpenAppMiniTemplatemessageSendRequest extends AbstractAlipayRequest
{
    /**
     * 小程序发送模板消息
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
