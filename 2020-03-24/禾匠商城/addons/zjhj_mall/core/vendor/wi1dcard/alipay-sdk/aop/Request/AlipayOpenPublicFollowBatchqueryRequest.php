<?php
/**
 * ALIPAY API: alipay.open.public.follow.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-20 10:26:41
 */

namespace Alipay\Request;

class AlipayOpenPublicFollowBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 获取关注者列表
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
