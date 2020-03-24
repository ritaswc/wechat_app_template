<?php
/**
 * ALIPAY API: alipay.marketing.card.activateform.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-13 16:55:38
 */

namespace Alipay\Request;

class AlipayMarketingCardActivateformQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询用户授权的开放表单信息
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
