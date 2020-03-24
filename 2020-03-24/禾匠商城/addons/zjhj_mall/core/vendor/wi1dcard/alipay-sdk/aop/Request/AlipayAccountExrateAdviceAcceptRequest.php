<?php
/**
 * ALIPAY API: alipay.account.exrate.advice.accept request
 *
 * @author auto create
 *
 * @since  1.0, 2016-05-23 14:55:42
 */

namespace Alipay\Request;

class AlipayAccountExrateAdviceAcceptRequest extends AbstractAlipayRequest
{
    /**
     * 标准的兑换交易受理接口
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
