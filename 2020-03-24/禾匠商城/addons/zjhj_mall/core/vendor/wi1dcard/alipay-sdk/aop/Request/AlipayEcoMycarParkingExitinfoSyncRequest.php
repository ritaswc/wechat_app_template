<?php
/**
 * ALIPAY API: alipay.eco.mycar.parking.exitinfo.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-25 17:10:12
 */

namespace Alipay\Request;

class AlipayEcoMycarParkingExitinfoSyncRequest extends AbstractAlipayRequest
{
    /**
     * 车辆驶出上送接口
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
