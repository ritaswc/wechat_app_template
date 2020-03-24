<?php
/**
 * ALIPAY API: mybank.finance.yulibao.price.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-11-18 16:08:17
 */

namespace Alipay\Request;

class MybankFinanceYulibaoPriceQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询余利宝行情信息（七日年化收益率、万份收益金额）
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
