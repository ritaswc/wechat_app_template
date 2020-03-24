<?php
/**
 * ALIPAY API: alipay.mobile.std.public.message.custom.send request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:19:52
 */

namespace Alipay\Request;

class AlipayMobileStdPublicMessageCustomSendRequest extends AbstractAlipayRequest
{
    /**
     * 业务内容，其中包括消息类型msgType、消息体和消息接受人toUserId三大块，具体参见“表1-2 服务窗单发客服消息的biz_content参数说明”。
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
