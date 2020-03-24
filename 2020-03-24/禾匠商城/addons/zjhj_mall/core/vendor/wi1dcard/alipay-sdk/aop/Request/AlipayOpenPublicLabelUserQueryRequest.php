<?php
/**
 * ALIPAY API: alipay.open.public.label.user.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:53:10
 */

namespace Alipay\Request;

class AlipayOpenPublicLabelUserQueryRequest extends AbstractAlipayRequest
{
    /**
     * 公众号标签管理-查询用户标签
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
