<?php
/**
 * ALIPAY API: mybank.finance.yulibao.capital.purchase request
 *
 * @author auto create
 *
 * @since  1.0, 2016-11-18 16:08:26
 */

namespace Alipay\Request;

class MybankFinanceYulibaoCapitalPurchaseRequest extends AbstractAlipayRequest
{
    /**
     * 网商银行余利宝签约
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
