<?php
/**
 * ALIPAY API: koubei.marketing.data.message.deliver request
 *
 * @author auto create
 *
 * @since  1.0, 2016-09-09 17:44:32
 */

namespace Alipay\Request;

class KoubeiMarketingDataMessageDeliverRequest extends AbstractAlipayRequest
{
    /**
     * 一键营销商家中心PUSH消息接口
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
