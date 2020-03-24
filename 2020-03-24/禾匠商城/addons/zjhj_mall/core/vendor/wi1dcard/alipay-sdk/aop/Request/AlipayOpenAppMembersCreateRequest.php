<?php
/**
 * ALIPAY API: alipay.open.app.members.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 12:09:54
 */

namespace Alipay\Request;

class AlipayOpenAppMembersCreateRequest extends AbstractAlipayRequest
{
    /**
     * 添加小程序开发者或体验者
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
