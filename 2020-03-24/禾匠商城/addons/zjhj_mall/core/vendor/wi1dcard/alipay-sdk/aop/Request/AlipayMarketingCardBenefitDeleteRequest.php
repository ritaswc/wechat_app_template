<?php
/**
 * ALIPAY API: alipay.marketing.card.benefit.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-23 09:40:27
 */

namespace Alipay\Request;

class AlipayMarketingCardBenefitDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡模板外部权益删除
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
