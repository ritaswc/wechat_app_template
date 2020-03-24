<?php
/**
 * ALIPAY API: alipay.eco.cplife.basicservice.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:19:45
 */

namespace Alipay\Request;

class AlipayEcoCplifeBasicserviceModifyRequest extends AbstractAlipayRequest
{
    /**
     * 修改小区物业基础服务信息
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
