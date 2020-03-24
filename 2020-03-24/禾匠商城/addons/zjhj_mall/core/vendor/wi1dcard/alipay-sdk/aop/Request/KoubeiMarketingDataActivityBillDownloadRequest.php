<?php
/**
 * ALIPAY API: koubei.marketing.data.activity.bill.download request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-28 21:22:31
 */

namespace Alipay\Request;

class KoubeiMarketingDataActivityBillDownloadRequest extends AbstractAlipayRequest
{
    /**
     * 营销报表下载
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
