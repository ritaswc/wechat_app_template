<?php
/**
 * ALIPAY API: alipay.pass.template.add request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-09 22:27:56
 */

namespace Alipay\Request;

class AlipayPassTemplateAddRequest extends AbstractAlipayRequest
{
    /**
     * 卡券模板创建
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
