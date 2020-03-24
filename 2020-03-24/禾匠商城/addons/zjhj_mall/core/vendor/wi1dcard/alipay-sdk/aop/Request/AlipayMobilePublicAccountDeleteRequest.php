<?php
/**
 * ALIPAY API: alipay.mobile.public.account.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2016-01-04 10:45:37
 */

namespace Alipay\Request;

class AlipayMobilePublicAccountDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 协议号等相关参数
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
