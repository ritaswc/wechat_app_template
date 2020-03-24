<?php
/**
 * ALIPAY API: alipay.open.app.members.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 13:56:08
 */

namespace Alipay\Request;

class AlipayOpenAppMembersDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除小程序成员
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
