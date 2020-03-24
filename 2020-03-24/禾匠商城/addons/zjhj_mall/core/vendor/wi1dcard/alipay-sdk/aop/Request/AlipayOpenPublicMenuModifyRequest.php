<?php
/**
 * ALIPAY API: alipay.open.public.menu.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-19 15:32:58
 */

namespace Alipay\Request;

class AlipayOpenPublicMenuModifyRequest extends AbstractAlipayRequest
{
    /**
     * 更新菜单
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
