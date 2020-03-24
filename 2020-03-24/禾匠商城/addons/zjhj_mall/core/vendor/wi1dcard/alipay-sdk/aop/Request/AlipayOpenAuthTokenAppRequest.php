<?php
/**
 * ALIPAY API: alipay.open.auth.token.app request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-29 18:00:00
 */

namespace Alipay\Request;

class AlipayOpenAuthTokenAppRequest extends AbstractAlipayRequest
{
    /**
     * 用应用授权码（app_auth_code）换取或者刷新应用授权令牌(app_auth_token)
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
