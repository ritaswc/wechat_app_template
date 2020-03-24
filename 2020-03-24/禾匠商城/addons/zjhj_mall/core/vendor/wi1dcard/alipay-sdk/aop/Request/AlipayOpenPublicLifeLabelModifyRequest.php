<?php
/**
 * ALIPAY API: alipay.open.public.life.label.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:07:52
 */

namespace Alipay\Request;

class AlipayOpenPublicLifeLabelModifyRequest extends AbstractAlipayRequest
{
    /**
     * 标签修改接口
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
