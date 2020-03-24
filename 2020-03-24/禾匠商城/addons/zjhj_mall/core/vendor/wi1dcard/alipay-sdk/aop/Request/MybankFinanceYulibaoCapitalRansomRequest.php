<?php
/**
 * ALIPAY API: mybank.finance.yulibao.capital.ransom request
 *
 * @author auto create
 *
 * @since  1.0, 2016-11-18 16:08:08
 */

namespace Alipay\Request;

class MybankFinanceYulibaoCapitalRansomRequest extends AbstractAlipayRequest
{
    /**
     * 网商银行余利宝赎回
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
