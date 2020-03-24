<?php
/**
 * ALIPAY API: alipay.fund.trans.toaccount.transfer request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-03 21:37:30
 */

namespace Alipay\Request;

class AlipayFundTransToaccountTransferRequest extends AbstractAlipayRequest
{
    /**
     * 单笔转账到支付宝账户接口
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
