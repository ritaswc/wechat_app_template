<?php
/**
 * ALIPAY API: alipay.open.public.account.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-04 10:40:27
 */

namespace Alipay\Request;

class AlipayOpenPublicAccountCreateRequest extends AbstractAlipayRequest
{
    /**
     * 添加绑定商户会员号
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
