<?php
/**
 * ALIPAY API: alipay.open.public.message.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-20 17:31:09
 */

namespace Alipay\Request;

class AlipayOpenPublicMessageQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询已发送消息接口
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
