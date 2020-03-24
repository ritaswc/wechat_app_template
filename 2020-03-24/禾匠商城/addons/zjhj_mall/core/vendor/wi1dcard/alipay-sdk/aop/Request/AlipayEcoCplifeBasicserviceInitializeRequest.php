<?php
/**
 * ALIPAY API: alipay.eco.cplife.basicservice.initialize request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:17:43
 */

namespace Alipay\Request;

class AlipayEcoCplifeBasicserviceInitializeRequest extends AbstractAlipayRequest
{
    /**
     * 初始化小区物业基础服务
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
