<?php
/**
 * ALIPAY API: alipay.marketing.card.template.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 10:55:00
 */

namespace Alipay\Request;

class AlipayMarketingCardTemplateModifyRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡模板修改
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
