<?php
/**
 * ALIPAY API: alipay.marketing.cashvoucher.template.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-05 14:38:00
 */

namespace Alipay\Request;

class AlipayMarketingCashvoucherTemplateCreateRequest extends AbstractAlipayRequest
{
    /**
     * 创建资金券模板
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
