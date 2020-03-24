<?php
/**
 * ALIPAY API: koubei.marketing.data.bizadviser.myddsreport.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-02 18:21:11
 */

namespace Alipay\Request;

class KoubeiMarketingDataBizadviserMyddsreportQueryRequest extends AbstractAlipayRequest
{
    /**
     * mydds 数据服务处理器
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
