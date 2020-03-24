<?php
/**
 * ALIPAY API: alipay.system.oauth.token request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-13 16:36:59
 */

namespace Alipay\Request;

class AlipaySystemOauthTokenRequest extends AbstractAlipayRequest
{
    /**
     * 授权码，用户对应用授权后得到。
     **/
    private $code;
    /**
     * 值为authorization_code时，代表用code换取；值为refresh_token时，代表用refresh_token换取
     **/
    private $grantType;
    /**
     * 刷新令牌，上次换取访问令牌时得到。见出参的refresh_token字段
     **/
    private $refreshToken;

    public function setCode($code)
    {
        $this->code = $code;
        $this->apiParams['code'] = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
        $this->apiParams['grant_type'] = $grantType;
    }

    public function getGrantType()
    {
        return $this->grantType;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        $this->apiParams['refresh_token'] = $refreshToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
