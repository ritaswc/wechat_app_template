<?php
/**
 * ALIPAY API: alipay.open.public.personalized.menu.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-27 14:37:28
 */

namespace Alipay\Request;

class AlipayOpenPublicPersonalizedMenuCreateRequest extends AbstractAlipayRequest
{
    /**
     * 个性化菜单创建
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
