<?php
/**
 * ALIPAY API: alipay.ins.auto.autoinsprod.quote.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-09 12:04:54
 */

namespace Alipay\Request;

class AlipayInsAutoAutoinsprodQuoteApplyRequest extends AbstractAlipayRequest
{
    /**
     * 报价申请接口
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
