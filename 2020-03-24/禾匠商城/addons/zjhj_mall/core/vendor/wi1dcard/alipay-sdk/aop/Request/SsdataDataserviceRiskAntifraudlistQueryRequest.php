<?php
/**
 * ALIPAY API: ssdata.dataservice.risk.antifraudlist.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-27 11:41:38
 */

namespace Alipay\Request;

class SsdataDataserviceRiskAntifraudlistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 蚁盾欺诈关注清单
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
