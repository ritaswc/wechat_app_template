<?php
/**
 * ALIPAY API: alipay.platform.userid.get request
 *
 * @author auto create
 *
 * @since  1.0, 2016-07-29 19:56:03
 */

namespace Alipay\Request;

class AlipayPlatformUseridGetRequest extends AbstractAlipayRequest
{
    /**
     * 根据OpenId获取UserId
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
