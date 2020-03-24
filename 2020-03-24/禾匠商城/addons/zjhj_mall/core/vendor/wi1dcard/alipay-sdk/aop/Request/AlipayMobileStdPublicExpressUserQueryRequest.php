<?php
/**
 * ALIPAY API: alipay.mobile.std.public.express.user.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:18:49
 */

namespace Alipay\Request;

class AlipayMobileStdPublicExpressUserQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询请求，用户id
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
