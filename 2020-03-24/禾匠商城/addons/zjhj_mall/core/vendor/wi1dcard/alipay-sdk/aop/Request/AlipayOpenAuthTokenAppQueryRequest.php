<?php
/**
 * ALIPAY API: alipay.open.auth.token.app.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-18 13:35:47
 */

namespace Alipay\Request;

class AlipayOpenAuthTokenAppQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询某个ISV下的指定app_auth_token的授权信息：授权者、授权接口列表、状态、过期时间等
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
