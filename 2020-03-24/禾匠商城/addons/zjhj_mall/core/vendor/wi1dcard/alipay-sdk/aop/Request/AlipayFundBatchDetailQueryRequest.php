<?php
/**
 * ALIPAY API: alipay.fund.batch.detail.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-24 11:35:00
 */

namespace Alipay\Request;

class AlipayFundBatchDetailQueryRequest extends AbstractAlipayRequest
{
    /**
     * 批量代发明细统一查询接口
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
