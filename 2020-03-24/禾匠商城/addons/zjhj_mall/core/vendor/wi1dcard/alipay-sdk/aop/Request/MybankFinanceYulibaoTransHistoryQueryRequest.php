<?php
/**
 * ALIPAY API: mybank.finance.yulibao.trans.history.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-11-18 16:07:59
 */

namespace Alipay\Request;

class MybankFinanceYulibaoTransHistoryQueryRequest extends AbstractAlipayRequest
{
    /**
     * 余利宝历史交易查询
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
