<?php
/**
 * ALIPAY API: alipay.open.public.template.message.industry.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-10 11:14:23
 */

namespace Alipay\Request;

class AlipayOpenPublicTemplateMessageIndustryModifyRequest extends AbstractAlipayRequest
{
    /**
     * 模板消息行业设置修改接口
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
