<?php
/**
 * ALIPAY API: alipay.mobile.public.menu.user.update request
 *
 * @author auto create
 *
 * @since  1.0, 2016-01-05 22:37:24
 */

namespace Alipay\Request;

class AlipayMobilePublicMenuUserUpdateRequest extends AbstractAlipayRequest
{
    /**
     * opendId和菜单唯一标识
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
