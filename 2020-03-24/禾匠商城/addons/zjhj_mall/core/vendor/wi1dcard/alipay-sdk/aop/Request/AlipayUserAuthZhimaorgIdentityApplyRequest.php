<?php
/**
 * ALIPAY API: alipay.user.auth.zhimaorg.identity.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-06 15:17:02
 */

namespace Alipay\Request;

class AlipayUserAuthZhimaorgIdentityApplyRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻企业征信基于身份的协议授权
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
