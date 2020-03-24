<?php
/**
 * ALIPAY API: alipay.open.public.default.extension.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:07:01
 */

namespace Alipay\Request;

class AlipayOpenPublicDefaultExtensionCreateRequest extends AbstractAlipayRequest
{
    /**
     * 默认扩展区创建接口
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
