<?php
/**
 * ALIPAY API: alipay.open.mini.experience.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 14:22:24
 */

namespace Alipay\Request;

class AlipayOpenMiniExperienceCreateRequest extends AbstractAlipayRequest
{
    /**
     * 小程序生成体验版
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
