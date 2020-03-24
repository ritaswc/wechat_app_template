<?php
/**
 * ALIPAY API: alipay.account.exrate.traderequest.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-13 17:35:02
 */

namespace Alipay\Request;

class AlipayAccountExrateTraderequestCreateRequest extends AbstractAlipayRequest
{
    /**
     * 受理外汇交易请求
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
