<?php
/**
 * ALIPAY API: alipay.eco.edu.kt.billing.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-06 11:48:51
 */

namespace Alipay\Request;

class AlipayEcoEduKtBillingModifyRequest extends AbstractAlipayRequest
{
    /**
     * 教育缴费账单状态同步接口
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
