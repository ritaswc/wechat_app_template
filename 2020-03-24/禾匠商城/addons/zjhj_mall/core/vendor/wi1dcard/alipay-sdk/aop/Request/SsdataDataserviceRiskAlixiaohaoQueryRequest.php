<?php
/**
 * ALIPAY API: ssdata.dataservice.risk.alixiaohao.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-26 14:38:27
 */

namespace Alipay\Request;

class SsdataDataserviceRiskAlixiaohaoQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询阿里通信小号信息
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
