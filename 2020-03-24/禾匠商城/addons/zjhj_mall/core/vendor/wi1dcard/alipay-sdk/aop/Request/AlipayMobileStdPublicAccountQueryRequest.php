<?php
/**
 * ALIPAY API: alipay.mobile.std.public.account.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:28:20
 */

namespace Alipay\Request;

class AlipayMobileStdPublicAccountQueryRequest extends AbstractAlipayRequest
{
    /**
     * 业务信息：userId，这是个json字段
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
