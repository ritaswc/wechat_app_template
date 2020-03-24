<?php
/**
 * ALIPAY API: alipay.eco.cplife.residentinfo.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:18:10
 */

namespace Alipay\Request;

class AlipayEcoCplifeResidentinfoDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除物业小区住户信息
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
