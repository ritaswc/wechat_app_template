<?php
/**
 * ALIPAY API: alipay.open.mini.version.gray.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 14:14:45
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionGrayCancelRequest extends AbstractAlipayRequest
{
    /**
     * 小程序结束灰度
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
