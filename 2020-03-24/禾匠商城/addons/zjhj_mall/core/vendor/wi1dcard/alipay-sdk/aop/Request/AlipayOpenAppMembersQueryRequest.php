<?php
/**
 * ALIPAY API: alipay.open.app.members.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 13:26:35
 */

namespace Alipay\Request;

class AlipayOpenAppMembersQueryRequest extends AbstractAlipayRequest
{
    /**
     * 小程序查询成员
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
