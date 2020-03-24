<?php
/**
 * ALIPAY API: alipay.open.public.xwbtestabcd.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-21 18:51:53
 */

namespace Alipay\Request;

class AlipayOpenPublicXwbtestabcdBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 预发上测试
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
