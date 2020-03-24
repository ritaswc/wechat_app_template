<?php
/**
 * ALIPAY API: alipay.platform.openid.get request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-06 17:38:21
 */

namespace Alipay\Request;

class AlipayPlatformOpenidGetRequest extends AbstractAlipayRequest
{
    /**
     * 业务内容，其中包括商户partner_id和用户ID列表user_ids两块
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
