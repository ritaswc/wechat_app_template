<?php
/**
 * ALIPAY API: alipay.open.public.personalized.menu.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-19 15:27:58
 */

namespace Alipay\Request;

class AlipayOpenPublicPersonalizedMenuDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 个性化菜单删除
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
