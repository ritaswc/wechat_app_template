<?php
/**
 * ALIPAY API: koubei.marketing.data.activity.report.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 16:53:34
 */

namespace Alipay\Request;

class KoubeiMarketingDataActivityReportQueryRequest extends AbstractAlipayRequest
{
    /**
     * 口碑商户营销报表查询
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
