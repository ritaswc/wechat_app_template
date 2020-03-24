<?php
/**
 * ALIPAY API: alipay.trade.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-19 16:09:44
 */

namespace Alipay\Request;

class AlipayTradeCancelRequest extends AbstractAlipayRequest
{
    /**
     * 统一收单交易撤销接口
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
