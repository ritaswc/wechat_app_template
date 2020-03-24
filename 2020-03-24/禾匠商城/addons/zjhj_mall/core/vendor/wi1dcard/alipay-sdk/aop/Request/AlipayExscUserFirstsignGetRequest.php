<?php
/**
 * ALIPAY API: alipay.exsc.user.firstsign.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-29 17:13:27
 */

namespace Alipay\Request;

class AlipayExscUserFirstsignGetRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝 cif的accountNo 格式：支付宝userId+0156
     **/
    private $alipayId;

    public function setAlipayId($alipayId)
    {
        $this->alipayId = $alipayId;
        $this->apiParams['alipay_id'] = $alipayId;
    }

    public function getAlipayId()
    {
        return $this->alipayId;
    }
}
