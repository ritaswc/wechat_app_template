<?php
/**
 * ALIPAY API: alipay.marketing.cashvoucher.template.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-20 18:10:10
 */

namespace Alipay\Request;

class AlipayMarketingCashvoucherTemplateModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改资金券模板
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
