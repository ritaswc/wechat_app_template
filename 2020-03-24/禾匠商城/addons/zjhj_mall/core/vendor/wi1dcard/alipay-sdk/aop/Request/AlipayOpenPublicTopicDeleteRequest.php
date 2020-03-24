<?php
/**
 * ALIPAY API: alipay.open.public.topic.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-05 15:03:11
 */

namespace Alipay\Request;

class AlipayOpenPublicTopicDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 营销位删除接口
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
