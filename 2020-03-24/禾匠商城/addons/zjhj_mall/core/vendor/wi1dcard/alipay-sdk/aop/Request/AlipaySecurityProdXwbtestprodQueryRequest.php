<?php
/**
 * ALIPAY API: alipay.security.prod.xwbtestprod.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-18 11:43:35
 */

namespace Alipay\Request;

class AlipaySecurityProdXwbtestprodQueryRequest extends AbstractAlipayRequest
{
    /**
     * 徐伟波测试用
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
