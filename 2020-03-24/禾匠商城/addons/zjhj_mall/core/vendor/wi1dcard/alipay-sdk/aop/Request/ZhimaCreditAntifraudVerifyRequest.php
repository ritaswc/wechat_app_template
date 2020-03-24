<?php
/**
 * ALIPAY API: zhima.credit.antifraud.verify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-13 19:33:16
 */

namespace Alipay\Request;

class ZhimaCreditAntifraudVerifyRequest extends AbstractAlipayRequest
{
    /**
     * 欺诈信息验证
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
