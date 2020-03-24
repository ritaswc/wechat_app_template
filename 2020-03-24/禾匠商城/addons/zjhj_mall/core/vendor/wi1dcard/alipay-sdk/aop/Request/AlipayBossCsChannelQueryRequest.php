<?php
/**
 * ALIPAY API: alipay.boss.cs.channel.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-02-23 20:04:44
 */

namespace Alipay\Request;

class AlipayBossCsChannelQueryRequest extends AbstractAlipayRequest
{
    /**
     * 云客服热线数据查询，云客服会有很多外部客服，他们需要查询落地在站内的自己公司的服务数据。
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
