<?php
/**
 * ALIPAY API: alipay.trade.close request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-14 16:27:58
 */

namespace Alipay\Request;

class AlipayTradeCloseRequest extends AbstractAlipayRequest
{
    /**
     * 统一收单交易关闭接口
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
