<?php
/**
 * ALIPAY API: alipay.commerce.data.monitordata.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-04 10:37:50
 */

namespace Alipay\Request;

class AlipayCommerceDataMonitordataSyncRequest extends AbstractAlipayRequest
{
    /**
     * 自助监控服务接口
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
