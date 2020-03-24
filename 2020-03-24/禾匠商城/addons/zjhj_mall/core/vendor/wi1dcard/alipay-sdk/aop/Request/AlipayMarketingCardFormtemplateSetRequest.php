<?php
/**
 * ALIPAY API: alipay.marketing.card.formtemplate.set request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 20:22:01
 */

namespace Alipay\Request;

class AlipayMarketingCardFormtemplateSetRequest extends AbstractAlipayRequest
{
    /**
     * 开卡组件表单配置
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
