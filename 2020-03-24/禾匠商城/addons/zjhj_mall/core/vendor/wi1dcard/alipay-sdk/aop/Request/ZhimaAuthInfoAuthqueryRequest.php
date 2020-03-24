<?php
/**
 * ALIPAY API: zhima.auth.info.authquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-19 18:38:54
 */

namespace Alipay\Request;

class ZhimaAuthInfoAuthqueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询是否授权的接口
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
