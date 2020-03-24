<?php
/**
 * ALIPAY API: alipay.open.public.message.content.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-02-09 15:49:52
 */

namespace Alipay\Request;

class AlipayOpenPublicMessageContentCreateRequest extends AbstractAlipayRequest
{
    /**
     * 新增图文消息素材接口
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
