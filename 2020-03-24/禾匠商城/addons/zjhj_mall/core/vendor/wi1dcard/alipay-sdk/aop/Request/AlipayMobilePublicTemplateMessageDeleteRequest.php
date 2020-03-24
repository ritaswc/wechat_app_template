<?php
/**
 * ALIPAY API: alipay.mobile.public.template.message.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-02 17:35:36
 */

namespace Alipay\Request;

class AlipayMobilePublicTemplateMessageDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 模板id
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
