<?php
/**
 * ALIPAY API: alipay.eco.edu.kt.billing.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-06 11:47:53
 */

namespace Alipay\Request;

class AlipayEcoEduKtBillingQueryRequest extends AbstractAlipayRequest
{
    /**
     * 缴费账单查询
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
