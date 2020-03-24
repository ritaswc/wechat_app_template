<?php
/**
 * ALIPAY API: alipay.open.public.life.msg.recall request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-28 17:30:50
 */

namespace Alipay\Request;

class AlipayOpenPublicLifeMsgRecallRequest extends AbstractAlipayRequest
{
    /**
     * 生活号消息撤回接口
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
