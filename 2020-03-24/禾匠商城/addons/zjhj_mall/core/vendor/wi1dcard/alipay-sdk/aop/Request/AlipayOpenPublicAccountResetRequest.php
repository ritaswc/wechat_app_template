<?php
/**
 * ALIPAY API: alipay.open.public.account.reset request
 *
 * @author auto create
 *
 * @since  1.0, 2016-12-08 12:01:32
 */

namespace Alipay\Request;

class AlipayOpenPublicAccountResetRequest extends AbstractAlipayRequest
{
    /**
     * 重新设置绑定商家会员号
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
