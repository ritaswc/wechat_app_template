<?php
/**
 * ALIPAY API: alipay.eco.welfare.code.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2016-11-02 19:53:47
 */

namespace Alipay\Request;

class AlipayEcoWelfareCodeSyncRequest extends AbstractAlipayRequest
{
    /**
     * 福利平台用户订单外部核销接口
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
