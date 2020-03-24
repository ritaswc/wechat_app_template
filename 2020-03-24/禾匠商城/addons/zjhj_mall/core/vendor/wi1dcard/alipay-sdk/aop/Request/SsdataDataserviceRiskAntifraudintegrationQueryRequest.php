<?php
/**
 * ALIPAY API: ssdata.dataservice.risk.antifraudintegration.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-08 14:09:21
 */

namespace Alipay\Request;

class SsdataDataserviceRiskAntifraudintegrationQueryRequest extends AbstractAlipayRequest
{
    /**
     * 蚁盾欺诈评分综合版
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
