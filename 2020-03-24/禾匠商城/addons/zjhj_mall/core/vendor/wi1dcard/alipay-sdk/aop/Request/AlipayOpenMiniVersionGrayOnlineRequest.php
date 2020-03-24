<?php
/**
 * ALIPAY API: alipay.open.mini.version.gray.online request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 13:10:51
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionGrayOnlineRequest extends AbstractAlipayRequest
{
    /**
     * 小程序灰度上架
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
