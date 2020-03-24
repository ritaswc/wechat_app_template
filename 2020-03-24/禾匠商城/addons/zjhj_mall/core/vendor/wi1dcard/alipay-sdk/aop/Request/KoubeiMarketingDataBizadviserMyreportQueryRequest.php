<?php
/**
 * ALIPAY API: koubei.marketing.data.bizadviser.myreport.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-02 18:20:49
 */

namespace Alipay\Request;

class KoubeiMarketingDataBizadviserMyreportQueryRequest extends AbstractAlipayRequest
{
    /**
     * 经营参谋数据报表处理器
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
