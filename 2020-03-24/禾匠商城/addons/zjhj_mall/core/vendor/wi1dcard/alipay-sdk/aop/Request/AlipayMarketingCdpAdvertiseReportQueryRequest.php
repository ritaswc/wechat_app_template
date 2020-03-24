<?php
/**
 * ALIPAY API: alipay.marketing.cdp.advertise.report.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-18 15:36:32
 */

namespace Alipay\Request;

class AlipayMarketingCdpAdvertiseReportQueryRequest extends AbstractAlipayRequest
{
    /**
     * 广告效果分析接口
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
