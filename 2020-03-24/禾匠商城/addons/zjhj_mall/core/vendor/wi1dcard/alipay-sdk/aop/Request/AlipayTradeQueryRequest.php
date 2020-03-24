<?php
/**
 * ALIPAY API: alipay.trade.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 18:28:47
 */

namespace Alipay\Request;

class AlipayTradeQueryRequest extends AbstractAlipayRequest
{
    /**
     * 统一收单线下交易查询
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
