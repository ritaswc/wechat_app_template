<?php
/**
 * ALIPAY API: alipay.eco.mycar.data.external.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-09 12:02:34
 */

namespace Alipay\Request;

class AlipayEcoMycarDataExternalSendRequest extends AbstractAlipayRequest
{
    /**
     * 行业平台写请求
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
