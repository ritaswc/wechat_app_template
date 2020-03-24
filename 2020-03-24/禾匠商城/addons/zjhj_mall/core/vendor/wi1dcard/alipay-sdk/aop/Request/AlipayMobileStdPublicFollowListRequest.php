<?php
/**
 * ALIPAY API: alipay.mobile.std.public.follow.list request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:38:32
 */

namespace Alipay\Request;

class AlipayMobileStdPublicFollowListRequest extends AbstractAlipayRequest
{
    /**
     * 当nextUserId为空时,代表查询第一组,如果有值时以当前值为准查询下一组
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
