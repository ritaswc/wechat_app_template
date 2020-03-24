<?php
/**
 * ALIPAY API: alipay.trust.user.token.get request
 *
 * @author auto create
 *
 * @since  1.0, 2015-05-06 18:13:09
 */

namespace Alipay\Request;

class AlipayTrustUserTokenGetRequest extends AbstractAlipayRequest
{
    /**
     * 入参json串
     **/
    private $aliTrustUserInfo;

    public function setAliTrustUserInfo($aliTrustUserInfo)
    {
        $this->aliTrustUserInfo = $aliTrustUserInfo;
        $this->apiParams['ali_trust_user_info'] = $aliTrustUserInfo;
    }

    public function getAliTrustUserInfo()
    {
        return $this->aliTrustUserInfo;
    }
}
