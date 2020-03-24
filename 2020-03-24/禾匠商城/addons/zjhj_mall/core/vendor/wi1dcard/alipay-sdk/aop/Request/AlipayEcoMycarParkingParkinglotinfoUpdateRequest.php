<?php
/**
 * ALIPAY API: alipay.eco.mycar.parking.parkinglotinfo.update request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-28 10:35:00
 */

namespace Alipay\Request;

class AlipayEcoMycarParkingParkinglotinfoUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 修改停车场信息
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
