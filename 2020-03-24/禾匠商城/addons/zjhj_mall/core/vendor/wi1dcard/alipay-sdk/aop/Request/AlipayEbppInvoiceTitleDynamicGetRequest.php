<?php
/**
 * ALIPAY API: alipay.ebpp.invoice.title.dynamic.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-02 10:30:28
 */

namespace Alipay\Request;

class AlipayEbppInvoiceTitleDynamicGetRequest extends AbstractAlipayRequest
{
    /**
     * 根据条形码获取抬头
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
