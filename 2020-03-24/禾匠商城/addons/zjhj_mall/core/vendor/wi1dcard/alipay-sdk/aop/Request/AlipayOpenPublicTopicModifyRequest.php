<?php
/**
 * ALIPAY API: alipay.open.public.topic.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-05 14:58:54
 */

namespace Alipay\Request;

class AlipayOpenPublicTopicModifyRequest extends AbstractAlipayRequest
{
    /**
     * 营销位修改接口
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
