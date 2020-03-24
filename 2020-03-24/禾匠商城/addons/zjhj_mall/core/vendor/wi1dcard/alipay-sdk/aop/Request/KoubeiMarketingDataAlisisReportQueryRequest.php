<?php
/**
 * ALIPAY API: koubei.marketing.data.alisis.report.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-03 09:41:08
 */

namespace Alipay\Request;

class KoubeiMarketingDataAlisisReportQueryRequest extends AbstractAlipayRequest
{
    /**
     * 报表详情查询接口
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
