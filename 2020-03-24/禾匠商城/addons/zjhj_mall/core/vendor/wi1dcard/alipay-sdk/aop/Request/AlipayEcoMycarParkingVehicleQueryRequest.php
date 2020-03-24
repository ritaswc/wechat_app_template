<?php
/**
 * ALIPAY API: alipay.eco.mycar.parking.vehicle.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-25 17:09:28
 */

namespace Alipay\Request;

class AlipayEcoMycarParkingVehicleQueryRequest extends AbstractAlipayRequest
{
    /**
     * 车牌查询接口
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
