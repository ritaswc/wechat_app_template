<?php
/**
 * ALIPAY API: alipay.open.mini.version.audited.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 13:29:48
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionAuditedCancelRequest extends AbstractAlipayRequest
{
    /**
     * 小程序退回开发
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
