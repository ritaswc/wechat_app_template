<?php
/**
 * ALIPAY API: alipay.open.public.label.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:55:16
 */

namespace Alipay\Request;

class AlipayOpenPublicLabelModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改标签接口
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
