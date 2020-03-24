<?php
/**
 * ALIPAY API: alipay.eco.mycar.parking.enterinfo.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-25 17:10:00
 */

namespace Alipay\Request;

class AlipayEcoMycarParkingEnterinfoSyncRequest extends AbstractAlipayRequest
{
    /**
     * 车辆驶入上送接口
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
