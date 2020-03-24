<?php
/**
 * ALIPAY API: alipay.open.mini.safedomain.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 15:25:38
 */

namespace Alipay\Request;

class AlipayOpenMiniSafedomainCreateRequest extends AbstractAlipayRequest
{
    /**
     * 小程序添加域白名单
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
