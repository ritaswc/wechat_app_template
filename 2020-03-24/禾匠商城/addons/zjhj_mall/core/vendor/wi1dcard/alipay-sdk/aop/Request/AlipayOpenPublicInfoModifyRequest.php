<?php
/**
 * ALIPAY API: alipay.open.public.info.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-12 16:54:39
 */

namespace Alipay\Request;

class AlipayOpenPublicInfoModifyRequest extends AbstractAlipayRequest
{
    /**
     * 服务窗基础信息修改接口
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
