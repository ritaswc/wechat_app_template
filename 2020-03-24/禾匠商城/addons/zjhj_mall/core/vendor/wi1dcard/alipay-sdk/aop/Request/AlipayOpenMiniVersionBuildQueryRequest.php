<?php
/**
 * ALIPAY API: alipay.open.mini.version.build.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-25 14:05:37
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionBuildQueryRequest extends AbstractAlipayRequest
{
    /**
     * 小程序构建状态查询
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
