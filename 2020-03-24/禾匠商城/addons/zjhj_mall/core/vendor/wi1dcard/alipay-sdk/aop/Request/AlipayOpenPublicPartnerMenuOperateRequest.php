<?php
/**
 * ALIPAY API: alipay.open.public.partner.menu.operate request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:32:33
 */

namespace Alipay\Request;

class AlipayOpenPublicPartnerMenuOperateRequest extends AbstractAlipayRequest
{
    /**
     * 为服务窗合作伙伴（如YunOS），提供操作服务窗菜单的功能
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
