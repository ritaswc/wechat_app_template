<?php
/**
 * ALIPAY API: alipay.open.mini.version.offline request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 14:16:24
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionOfflineRequest extends AbstractAlipayRequest
{
    /**
     * 小程序下架
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
