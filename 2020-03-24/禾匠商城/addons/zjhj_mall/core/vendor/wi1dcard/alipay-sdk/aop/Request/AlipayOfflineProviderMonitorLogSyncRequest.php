<?php
/**
 * ALIPAY API: alipay.offline.provider.monitor.log.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2016-09-28 11:33:15
 */

namespace Alipay\Request;

class AlipayOfflineProviderMonitorLogSyncRequest extends AbstractAlipayRequest
{
    /**
     * ISV服务商监控数据回流
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
