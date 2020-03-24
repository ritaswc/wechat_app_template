<?php
/**
 * ALIPAY API: alipay.offline.market.reporterror.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-03 14:41:37
 */

namespace Alipay\Request;

class AlipayOfflineMarketReporterrorCreateRequest extends AbstractAlipayRequest
{
    /**
     * 上报线下服务异常
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
