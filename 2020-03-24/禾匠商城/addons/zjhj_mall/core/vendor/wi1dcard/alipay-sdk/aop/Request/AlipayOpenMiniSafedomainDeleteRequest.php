<?php
/**
 * ALIPAY API: alipay.open.mini.safedomain.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-15 14:42:34
 */

namespace Alipay\Request;

class AlipayOpenMiniSafedomainDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 小程序删除域白名单
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
