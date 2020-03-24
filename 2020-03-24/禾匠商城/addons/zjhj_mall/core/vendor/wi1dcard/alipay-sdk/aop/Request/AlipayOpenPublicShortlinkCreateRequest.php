<?php
/**
 * ALIPAY API: alipay.open.public.shortlink.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-04 10:40:37
 */

namespace Alipay\Request;

class AlipayOpenPublicShortlinkCreateRequest extends AbstractAlipayRequest
{
    /**
     * 服务窗短链自主生成接口
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
