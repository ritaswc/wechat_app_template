<?php
/**
 * ALIPAY API: alipay.app.token.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-13 19:13:06
 */

namespace Alipay\Request;

class AlipayAppTokenGetRequest extends AbstractAlipayRequest
{
    /**
     * 应用安全码
     **/
    private $secret;

    public function setSecret($secret)
    {
        $this->secret = $secret;
        $this->apiParams['secret'] = $secret;
    }

    public function getSecret()
    {
        return $this->secret;
    }
}
