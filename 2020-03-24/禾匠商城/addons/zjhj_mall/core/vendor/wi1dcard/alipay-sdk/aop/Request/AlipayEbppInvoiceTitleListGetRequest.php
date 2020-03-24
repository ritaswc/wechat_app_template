<?php
/**
 * ALIPAY API: alipay.ebpp.invoice.title.list.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-02 10:28:56
 */

namespace Alipay\Request;

class AlipayEbppInvoiceTitleListGetRequest extends AbstractAlipayRequest
{
    /**
     * 蚂蚁电子发票平台用户发票抬头列表获取
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
