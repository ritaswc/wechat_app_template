<?php
/**
 * ALIPAY API: alipay.mobile.public.message.total.send request
 *
 * @author auto create
 *
 * @since  1.0, 2016-01-19 16:43:05
 */

namespace Alipay\Request;

class AlipayMobilePublicMessageTotalSendRequest extends AbstractAlipayRequest
{
    /**
     * 业务内容，其中包括消息类型msgType和消息体两部分，具体参见“表1-2 服务窗群发消息的biz_content参数说明”。
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
