<?php
/**
 * ALIPAY API: alipay.open.public.partner.menu.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:23:59
 */

namespace Alipay\Request;

class AlipayOpenPublicPartnerMenuQueryRequest extends AbstractAlipayRequest
{
    /**
     * 为服务窗合作伙伴（如YunOS），提供查询所有服务窗的菜单的功能
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
