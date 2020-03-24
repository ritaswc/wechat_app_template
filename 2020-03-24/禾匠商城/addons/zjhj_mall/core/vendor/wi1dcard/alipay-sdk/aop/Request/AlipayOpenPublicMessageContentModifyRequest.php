<?php
/**
 * ALIPAY API: alipay.open.public.message.content.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-09 16:28:22
 */

namespace Alipay\Request;

class AlipayOpenPublicMessageContentModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改图文消息素材接口
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
