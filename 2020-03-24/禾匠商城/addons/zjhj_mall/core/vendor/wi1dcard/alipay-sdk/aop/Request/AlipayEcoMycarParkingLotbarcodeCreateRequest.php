<?php
/**
 * ALIPAY API: alipay.eco.mycar.parking.lotbarcode.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-08-25 17:10:44
 */

namespace Alipay\Request;

class AlipayEcoMycarParkingLotbarcodeCreateRequest extends AbstractAlipayRequest
{
    /**
     * 物料二维码
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
