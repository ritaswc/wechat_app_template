<?php
/**
 * ALIPAY API: alipay.open.mini.templatemessage.usertemplate.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-23 18:22:47
 */

namespace Alipay\Request;

class AlipayOpenMiniTemplatemessageUsertemplateApplyRequest extends AbstractAlipayRequest
{
    /**
     * 小程序模板消息申请
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
