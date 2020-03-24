<?php
/**
 * ALIPAY API: alipay.fund.batch.transfer request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-21 20:55:00
 */

namespace Alipay\Request;

class AlipayFundBatchTransferRequest extends AbstractAlipayRequest
{
    /**
     * 批量代发统一接口
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
