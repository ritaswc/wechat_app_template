<?php
/**
 * ALIPAY API: alipay.ins.auto.car.save request
 *
 * @author auto create
 *
 * @since  1.0, 2016-05-18 15:27:46
 */

namespace Alipay\Request;

class AlipayInsAutoCarSaveRequest extends AbstractAlipayRequest
{
    /**
     * 蚂蚁乐驾车主车辆保存服务
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
