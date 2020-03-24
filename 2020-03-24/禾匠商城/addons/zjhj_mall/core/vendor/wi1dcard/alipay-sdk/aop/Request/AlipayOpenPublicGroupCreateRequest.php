<?php
/**
 * ALIPAY API: alipay.open.public.group.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:05:47
 */

namespace Alipay\Request;

class AlipayOpenPublicGroupCreateRequest extends AbstractAlipayRequest
{
    /**
     * 用户分组创建接口
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
