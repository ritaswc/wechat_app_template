<?php
/**
 * ALIPAY API: ssdata.dataservice.risk.antifraud.verify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-27 11:42:12
 */

namespace Alipay\Request;

class SsdataDataserviceRiskAntifraudVerifyRequest extends AbstractAlipayRequest
{
    /**
     * 蚁盾欺诈信息验证
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
