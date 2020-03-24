<?php
/**
 * ALIPAY API: alipay.open.public.message.custom.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-01 10:35:00
 */

namespace Alipay\Request;

class AlipayOpenPublicMessageCustomSendRequest extends AbstractAlipayRequest
{
    /**
     * 异步单发消息
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
