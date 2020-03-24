<?php
/**
 * ALIPAY API: alipay.marketing.cashlessvoucher.template.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-22 10:20:00
 */

namespace Alipay\Request;

class AlipayMarketingCashlessvoucherTemplateCreateRequest extends AbstractAlipayRequest
{
    /**
     * 商户券模板创建接口
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
