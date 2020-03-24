<?php
/**
 * ALIPAY API: alipay.open.public.singlearticle.data.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-05 15:06:32
 */

namespace Alipay\Request;

class AlipayOpenPublicSinglearticleDataBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 图文分析-按文章查询数据接口
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
