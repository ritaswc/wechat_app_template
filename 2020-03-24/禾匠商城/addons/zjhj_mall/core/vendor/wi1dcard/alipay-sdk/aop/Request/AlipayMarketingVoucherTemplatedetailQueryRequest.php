<?php
/**
 * ALIPAY API: alipay.marketing.voucher.templatedetail.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-20 18:10:39
 */

namespace Alipay\Request;

class AlipayMarketingVoucherTemplatedetailQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询模板详情
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
