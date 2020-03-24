<?php
/**
 * ALIPAY API: alipay.commerce.transport.offlinepay.userblacklist.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-04 17:13:07
 */

namespace Alipay\Request;

class AlipayCommerceTransportOfflinepayUserblacklistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 脱机交易黑名单列表
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
