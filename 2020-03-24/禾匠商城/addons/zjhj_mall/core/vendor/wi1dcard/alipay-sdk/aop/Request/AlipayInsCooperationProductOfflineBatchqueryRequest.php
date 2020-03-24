<?php
/**
 * ALIPAY API: alipay.ins.cooperation.product.offline.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-24 10:29:36
 */

namespace Alipay\Request;

class AlipayInsCooperationProductOfflineBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * （快捷投保）查询该保险公司的线下产品列表
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
