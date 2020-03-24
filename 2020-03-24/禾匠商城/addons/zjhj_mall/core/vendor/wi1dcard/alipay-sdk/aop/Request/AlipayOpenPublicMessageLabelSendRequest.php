<?php
/**
 * ALIPAY API: alipay.open.public.message.label.send request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:43:52
 */

namespace Alipay\Request;

class AlipayOpenPublicMessageLabelSendRequest extends AbstractAlipayRequest
{
    /**
     * 根据标签组发消息接口
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
