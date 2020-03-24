<?php
/**
 * ALIPAY API: alipay.open.mini.version.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-18 11:16:19
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 小程序删除版本
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
