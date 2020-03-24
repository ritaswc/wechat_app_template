<?php
/**
 * ALIPAY API: alipay.eco.mycar.parking.config.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-25 14:53:00
 */

namespace Alipay\Request;

class AlipayEcoMycarParkingConfigQueryRequest extends AbstractAlipayRequest
{
    /**
     * ISV系统配置查询接口
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
