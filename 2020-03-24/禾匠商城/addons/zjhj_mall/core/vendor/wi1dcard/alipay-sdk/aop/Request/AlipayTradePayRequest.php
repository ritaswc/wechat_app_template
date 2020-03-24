<?php
/**
 * ALIPAY API: alipay.trade.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 18:10:00
 */

namespace Alipay\Request;

class AlipayTradePayRequest extends AbstractAlipayRequest
{
    /**
     * 用于在线下场景交易一次创建并支付掉
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
