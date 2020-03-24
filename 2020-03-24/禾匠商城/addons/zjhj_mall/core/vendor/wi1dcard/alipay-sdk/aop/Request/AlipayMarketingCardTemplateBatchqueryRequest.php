<?php
/**
 * ALIPAY API: alipay.marketing.card.template.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-05 17:51:11
 */

namespace Alipay\Request;

class AlipayMarketingCardTemplateBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡模板批量查询
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
