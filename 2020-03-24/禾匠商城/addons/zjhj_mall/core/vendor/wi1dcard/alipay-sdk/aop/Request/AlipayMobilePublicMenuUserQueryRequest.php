<?php
/**
 * ALIPAY API: alipay.mobile.public.menu.user.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-01-12 17:25:25
 */

namespace Alipay\Request;

class AlipayMobilePublicMenuUserQueryRequest extends AbstractAlipayRequest
{
    /**
     * 用户openId
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
