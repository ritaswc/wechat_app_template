<?php
/**
 * ALIPAY API: alipay.ins.scene.application.issue.confirm request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-03 11:29:12
 */

namespace Alipay\Request;

class AlipayInsSceneApplicationIssueConfirmRequest extends AbstractAlipayRequest
{
    /**
     * 投保订单出单确认
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
