<?php
/**
 * ALIPAY API: alipay.trade.precreate request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 17:32:25
 */

namespace Alipay\Request;

class AlipayTradePrecreateRequest extends AbstractAlipayRequest
{
    /**
     * 收银员通过收银台或商户后台调用支付宝接口，生成二维码后，展示给伤脑筋户，由用户扫描二维码完成订单支付。
     * 修改路由策略到R
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
