<?php
/**
 * ALIPAY API: alipay.marketing.card.update request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-24 15:51:39
 */

namespace Alipay\Request;

class AlipayMarketingCardUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡更新
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
