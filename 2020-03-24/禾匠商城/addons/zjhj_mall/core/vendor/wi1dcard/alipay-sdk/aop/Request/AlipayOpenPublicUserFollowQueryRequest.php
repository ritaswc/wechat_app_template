<?php
/**
 * ALIPAY API: alipay.open.public.user.follow.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-02 16:05:24
 */

namespace Alipay\Request;

class AlipayOpenPublicUserFollowQueryRequest extends AbstractAlipayRequest
{
    /**
     * 生活号用户关注查询接口
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
