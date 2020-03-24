<?php
/**
 * ALIPAY API: alipay.open.public.topic.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-05 17:38:29
 */

namespace Alipay\Request;

class AlipayOpenPublicTopicCreateRequest extends AbstractAlipayRequest
{
    /**
     * 营销位添加接口
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
