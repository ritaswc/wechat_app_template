<?php
/**
 * ALIPAY API: alipay.open.public.personalized.extension.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:08:24
 */

namespace Alipay\Request;

class AlipayOpenPublicPersonalizedExtensionCreateRequest extends AbstractAlipayRequest
{
    /**
     * 个性化扩展区创建接口
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
