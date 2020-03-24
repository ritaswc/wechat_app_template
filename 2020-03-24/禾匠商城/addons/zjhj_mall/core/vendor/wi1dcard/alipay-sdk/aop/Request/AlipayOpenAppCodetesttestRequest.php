<?php
/**
 * ALIPAY API: alipay.open.app.codetesttest request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-12 20:55:36
 */

namespace Alipay\Request;

class AlipayOpenAppCodetesttestRequest extends AbstractAlipayRequest
{
    /**
     * 统一对外错误码测试测试
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
