<?php
/**
 * ALIPAY API: alipay.open.public.message.group.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:07:35
 */

namespace Alipay\Request;

class AlipayOpenPublicMessageGroupSendRequest extends AbstractAlipayRequest
{
    /**
     * 分组消息发送接口
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
