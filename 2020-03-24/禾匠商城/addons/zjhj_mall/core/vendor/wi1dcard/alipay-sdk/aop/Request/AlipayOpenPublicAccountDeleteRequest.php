<?php
/**
 * ALIPAY API: alipay.open.public.account.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 11:46:14
 */

namespace Alipay\Request;

class AlipayOpenPublicAccountDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 解除绑定商户会员号
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
