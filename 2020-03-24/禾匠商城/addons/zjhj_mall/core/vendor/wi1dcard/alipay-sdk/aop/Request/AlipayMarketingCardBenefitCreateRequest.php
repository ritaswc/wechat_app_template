<?php
/**
 * ALIPAY API: alipay.marketing.card.benefit.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-11 17:31:14
 */

namespace Alipay\Request;

class AlipayMarketingCardBenefitCreateRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡模板外部权益创建
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
