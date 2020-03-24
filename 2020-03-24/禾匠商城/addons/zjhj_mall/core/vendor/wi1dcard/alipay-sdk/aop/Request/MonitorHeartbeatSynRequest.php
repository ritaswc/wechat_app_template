<?php
/**
 * ALIPAY API: monitor.heartbeat.syn request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-24 20:12:25
 */

namespace Alipay\Request;

class MonitorHeartbeatSynRequest extends AbstractAlipayRequest
{
    /**
     * 验签时该参数不做任何处理
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
