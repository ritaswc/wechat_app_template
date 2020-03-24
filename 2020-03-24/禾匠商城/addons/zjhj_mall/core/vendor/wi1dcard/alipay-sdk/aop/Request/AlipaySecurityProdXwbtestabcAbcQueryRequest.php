<?php
/**
 * ALIPAY API: alipay.security.prod.xwbtestabc.abc.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-17 11:00:06
 */

namespace Alipay\Request;

class AlipaySecurityProdXwbtestabcAbcQueryRequest extends AbstractAlipayRequest
{
    /**
     * xuwebio测试用
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
