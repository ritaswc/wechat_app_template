<?php
/**
 * ALIPAY API: alipay.pass.template.update request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-09 22:28:13
 */

namespace Alipay\Request;

class AlipayPassTemplateUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝pass更新模版接口
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
