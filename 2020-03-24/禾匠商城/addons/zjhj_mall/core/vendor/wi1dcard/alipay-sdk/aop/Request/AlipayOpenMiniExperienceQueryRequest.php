<?php
/**
 * ALIPAY API: alipay.open.mini.experience.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-25 00:12:45
 */

namespace Alipay\Request;

class AlipayOpenMiniExperienceQueryRequest extends AbstractAlipayRequest
{
    /**
     * 小程序体验版轮询接口
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
