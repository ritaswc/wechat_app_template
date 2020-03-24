<?php
/**
 * ALIPAY API: alipay.mobile.public.message.push request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-31 21:05:52
 */

namespace Alipay\Request;

class AlipayMobilePublicMessagePushRequest extends AbstractAlipayRequest
{
    /**
     * 业务内容JSON
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
