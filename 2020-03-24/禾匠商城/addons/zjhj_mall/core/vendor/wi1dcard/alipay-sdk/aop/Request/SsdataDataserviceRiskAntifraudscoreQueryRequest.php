<?php
/**
 * ALIPAY API: ssdata.dataservice.risk.antifraudscore.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-27 11:42:00
 */

namespace Alipay\Request;

class SsdataDataserviceRiskAntifraudscoreQueryRequest extends AbstractAlipayRequest
{
    /**
     * 蚁盾申请欺诈评分
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
