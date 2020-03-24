<?php
/**
 * ALIPAY API: alipay.account.exrate.allclientrate.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-12 19:07:12
 */

namespace Alipay\Request;

class AlipayAccountExrateAllclientrateQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询客户的所有币种对最新有效汇率
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
