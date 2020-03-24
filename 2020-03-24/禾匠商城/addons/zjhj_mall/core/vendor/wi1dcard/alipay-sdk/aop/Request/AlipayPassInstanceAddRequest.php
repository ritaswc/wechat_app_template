<?php
/**
 * ALIPAY API: alipay.pass.instance.add request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-02 15:50:00
 */

namespace Alipay\Request;

class AlipayPassInstanceAddRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝pass新建卡券实例接口
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
