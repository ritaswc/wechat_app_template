<?php
/**
 * ALIPAY API: alipay.open.public.life.label.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:08:08
 */

namespace Alipay\Request;

class AlipayOpenPublicLifeLabelCreateRequest extends AbstractAlipayRequest
{
    /**
     * 创建标签接口
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
