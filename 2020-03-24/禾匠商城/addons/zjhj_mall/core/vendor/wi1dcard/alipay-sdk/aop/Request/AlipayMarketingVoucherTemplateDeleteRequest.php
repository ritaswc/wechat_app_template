<?php
/**
 * ALIPAY API: alipay.marketing.voucher.template.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-30 22:43:25
 */

namespace Alipay\Request;

class AlipayMarketingVoucherTemplateDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除资金券模板
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
