<?php
/**
 * ALIPAY API: alipay.trade.customs.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-02 14:37:16
 */

namespace Alipay\Request;

class AlipayTradeCustomsQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询报关详细信息
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
