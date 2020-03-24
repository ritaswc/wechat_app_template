<?php
/**
 * ALIPAY API: alipay.eco.cplife.community.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:14:53
 */

namespace Alipay\Request;

class AlipayEcoCplifeCommunityBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 批量查询支付宝小区编号
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
