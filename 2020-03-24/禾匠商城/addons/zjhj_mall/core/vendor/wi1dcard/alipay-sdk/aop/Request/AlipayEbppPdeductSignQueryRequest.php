<?php
/**
 * ALIPAY API: alipay.ebpp.pdeduct.sign.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-02 20:27:24
 */

namespace Alipay\Request;

class AlipayEbppPdeductSignQueryRequest extends AbstractAlipayRequest
{
    /**
     * 直连代扣协议查询接口
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
