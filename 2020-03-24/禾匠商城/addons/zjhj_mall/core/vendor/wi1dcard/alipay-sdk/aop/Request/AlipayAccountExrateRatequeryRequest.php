<?php
/**
 * ALIPAY API: alipay.account.exrate.ratequery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-03-27 18:11:27
 */

namespace Alipay\Request;

class AlipayAccountExrateRatequeryRequest extends AbstractAlipayRequest
{
    /**
     * 对于部分签约境内当面付的商家，为了能够在境外进行推广，因此需要汇率进行币种之间的转换，本接口提供此业务场景下的汇率查询服务
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
