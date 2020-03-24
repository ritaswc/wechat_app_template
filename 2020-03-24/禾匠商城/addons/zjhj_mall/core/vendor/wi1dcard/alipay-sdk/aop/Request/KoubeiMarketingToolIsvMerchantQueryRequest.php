<?php
/**
 * ALIPAY API: koubei.marketing.tool.isv.merchant.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-04 15:15:46
 */

namespace Alipay\Request;

class KoubeiMarketingToolIsvMerchantQueryRequest extends AbstractAlipayRequest
{
    /**
     * ISV查询商户列表接口
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
