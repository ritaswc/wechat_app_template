<?php
/**
 * ALIPAY API: alipay.open.public.articlesummary.data.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-05 15:08:10
 */

namespace Alipay\Request;

class AlipayOpenPublicArticlesummaryDataBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 图文分析-按时间查询数据接口
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
