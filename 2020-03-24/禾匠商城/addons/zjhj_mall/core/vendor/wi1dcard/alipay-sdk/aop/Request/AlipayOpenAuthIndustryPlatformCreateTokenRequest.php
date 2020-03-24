<?php
/**
 * ALIPAY API: alipay.open.auth.industry.platform.create.token request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-09 11:39:27
 */

namespace Alipay\Request;

class AlipayOpenAuthIndustryPlatformCreateTokenRequest extends AbstractAlipayRequest
{
    /**
     * 行业平台获取授权码
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
