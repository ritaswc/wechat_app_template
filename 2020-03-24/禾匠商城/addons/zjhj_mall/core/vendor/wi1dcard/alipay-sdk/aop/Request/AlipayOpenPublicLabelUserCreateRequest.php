<?php
/**
 * ALIPAY API: alipay.open.public.label.user.create request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:54:45
 */

namespace Alipay\Request;

class AlipayOpenPublicLabelUserCreateRequest extends AbstractAlipayRequest
{
    /**
     * 用户增加标签接口
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
