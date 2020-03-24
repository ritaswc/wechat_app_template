<?php
/**
 * ALIPAY API: alipay.mobile.public.menu.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-31 21:02:26
 */

namespace Alipay\Request;

class AlipayMobilePublicMenuDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 菜单唯一标识
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
