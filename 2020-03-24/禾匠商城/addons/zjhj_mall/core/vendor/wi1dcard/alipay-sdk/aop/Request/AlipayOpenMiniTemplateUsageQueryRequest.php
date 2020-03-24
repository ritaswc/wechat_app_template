<?php
/**
 * ALIPAY API: alipay.open.mini.template.usage.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-14 14:04:01
 */

namespace Alipay\Request;

class AlipayOpenMiniTemplateUsageQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询使用ISV模板的托管小程序列表
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
