<?php
/**
 * ALIPAY API: alipay.open.public.personalized.extension.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:07:10
 */

namespace Alipay\Request;

class AlipayOpenPublicPersonalizedExtensionDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 个性化扩展区删除接口
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
