<?php
/**
 * ALIPAY API: alipay.marketing.card.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-16 19:51:58
 */

namespace Alipay\Request;

class AlipayMarketingCardDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡删卡
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
