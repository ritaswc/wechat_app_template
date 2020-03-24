<?php
/**
 * ALIPAY API: alipay.marketing.card.template.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 10:45:00
 */

namespace Alipay\Request;

class AlipayMarketingCardTemplateCreateRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡模板创建
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
