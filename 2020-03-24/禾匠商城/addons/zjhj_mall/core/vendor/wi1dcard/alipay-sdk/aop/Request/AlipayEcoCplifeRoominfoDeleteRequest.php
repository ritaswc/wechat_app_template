<?php
/**
 * ALIPAY API: alipay.eco.cplife.roominfo.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:18:50
 */

namespace Alipay\Request;

class AlipayEcoCplifeRoominfoDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除物业小区房屋信息
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
