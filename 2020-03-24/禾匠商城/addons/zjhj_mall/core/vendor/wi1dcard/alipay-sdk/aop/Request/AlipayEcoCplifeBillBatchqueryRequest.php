<?php
/**
 * ALIPAY API: alipay.eco.cplife.bill.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:13:42
 */

namespace Alipay\Request;

class AlipayEcoCplifeBillBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 物业费账单数据批量查询
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
