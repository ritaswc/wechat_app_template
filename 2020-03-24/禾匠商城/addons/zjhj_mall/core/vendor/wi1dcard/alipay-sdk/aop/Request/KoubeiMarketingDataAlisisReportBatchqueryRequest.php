<?php
/**
 * ALIPAY API: koubei.marketing.data.alisis.report.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-03 09:40:58
 */

namespace Alipay\Request;

class KoubeiMarketingDataAlisisReportBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 报表列表查询接口
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
