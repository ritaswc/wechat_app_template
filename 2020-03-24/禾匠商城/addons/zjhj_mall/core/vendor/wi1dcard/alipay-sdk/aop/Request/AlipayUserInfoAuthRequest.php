<?php
/**
 * ALIPAY API: alipay.user.info.auth request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-13 17:20:12
 */

namespace Alipay\Request;

class AlipayUserInfoAuthRequest extends AbstractAlipayRequest
{
    /**
     * 用户登陆授权
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
