<?php
/**
 * ALIPAY API: alipay.open.public.template.message.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-26 16:25:00
 */

namespace Alipay\Request;

class AlipayOpenPublicTemplateMessageGetRequest extends AbstractAlipayRequest
{
    /**
     * 模板消息领取接口
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
