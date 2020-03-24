<?php
/**
 * ALIPAY API: ssdata.dataservice.risk.rainscore.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-08 14:08:19
 */

namespace Alipay\Request;

class SsdataDataserviceRiskRainscoreQueryRequest extends AbstractAlipayRequest
{
    /**
     * 蚁盾风险评分服务新版
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
