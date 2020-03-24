<?php
/**
 * ALIPAY API: alipay.marketing.card.open request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-04 19:47:19
 */

namespace Alipay\Request;

class AlipayMarketingCardOpenRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡开卡接口
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
