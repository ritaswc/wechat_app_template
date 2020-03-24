<?php
/**
 * ALIPAY API: alipay.mobile.code.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 11:34:53
 */

namespace Alipay\Request;

class AlipayMobileCodeQueryRequest extends AbstractAlipayRequest
{
    /**
     * 二维码的码值或者包含业务信息的二维码
     **/
    private $qrToken;

    public function setQrToken($qrToken)
    {
        $this->qrToken = $qrToken;
        $this->apiParams['qr_token'] = $qrToken;
    }

    public function getQrToken()
    {
        return $this->qrToken;
    }
}
