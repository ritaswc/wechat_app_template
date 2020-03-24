<?php
/**
 * ALIPAY API: alipay.fund.auth.operation.cancel request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-07 14:45:00
 */

namespace Alipay\Request;

class AlipayFundAuthOperationCancelRequest extends AbstractAlipayRequest
{
    /**
     * 资金预授权明细撤销接口
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
