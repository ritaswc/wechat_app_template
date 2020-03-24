<?php
/**
 * ALIPAY API: alipay.open.public.group.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:06:31
 */

namespace Alipay\Request;

class AlipayOpenPublicGroupDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 用户分组删除接口
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
