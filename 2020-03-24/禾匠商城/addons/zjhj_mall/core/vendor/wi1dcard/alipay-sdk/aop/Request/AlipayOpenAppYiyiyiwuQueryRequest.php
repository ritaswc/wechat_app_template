<?php
/**
 * ALIPAY API: alipay.open.app.yiyiyiwu.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-15 13:09:47
 */

namespace Alipay\Request;

class AlipayOpenAppYiyiyiwuQueryRequest extends AbstractAlipayRequest
{
    /**
     * 预发验证一一一五
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
