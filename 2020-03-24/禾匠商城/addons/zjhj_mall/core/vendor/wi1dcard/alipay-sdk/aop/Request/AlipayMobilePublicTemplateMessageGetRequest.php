<?php
/**
 * ALIPAY API: alipay.mobile.public.template.message.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-02 17:37:08
 */

namespace Alipay\Request;

class AlipayMobilePublicTemplateMessageGetRequest extends AbstractAlipayRequest
{
    /**
     * 消息母板id
     **/
    private $templateId;

    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        $this->apiParams['template_id'] = $templateId;
    }

    public function getTemplateId()
    {
        return $this->templateId;
    }
}
