<?php
/**
 * ALIPAY API: alipay.mobile.public.template.message.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:26:03
 */

namespace Alipay\Request;

class AlipayMobilePublicTemplateMessageModifyRequest extends AbstractAlipayRequest
{
    /**
     * 模板id
     **/
    private $templateId;
    /**
     * 行业设置
     **/
    private $tradeSetting;

    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        $this->apiParams['template_id'] = $templateId;
    }

    public function getTemplateId()
    {
        return $this->templateId;
    }

    public function setTradeSetting($tradeSetting)
    {
        $this->tradeSetting = $tradeSetting;
        $this->apiParams['trade_setting'] = $tradeSetting;
    }

    public function getTradeSetting()
    {
        return $this->tradeSetting;
    }
}
