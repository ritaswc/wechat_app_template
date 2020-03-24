<?php
/**
 * ALIPAY API: alipay.open.public.payee.bind.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-28 16:40:00
 */

namespace Alipay\Request;

class AlipayOpenPublicPayeeBindCreateRequest extends AbstractAlipayRequest
{
    /**
     * 添加收款账号接口
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
