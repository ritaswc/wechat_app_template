<?php
/**
 * ALIPAY API: alipay.marketing.cashlessvoucher.template.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-30 22:40:50
 */

namespace Alipay\Request;

class AlipayMarketingCashlessvoucherTemplateModifyRequest extends AbstractAlipayRequest
{
    /**
     * 商户券模板修改接口
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
