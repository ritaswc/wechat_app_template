<?php
/**
 * ALIPAY API: alipay.mobile.public.account.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-31 21:02:46
 */

namespace Alipay\Request;

class AlipayMobilePublicAccountQueryRequest extends AbstractAlipayRequest
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
