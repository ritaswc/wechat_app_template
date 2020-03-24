<?php
/**
 * ALIPAY API: alipay.open.public.user.data.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-05 15:05:24
 */

namespace Alipay\Request;

class AlipayOpenPublicUserDataBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 用户分析数据查询接口
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
