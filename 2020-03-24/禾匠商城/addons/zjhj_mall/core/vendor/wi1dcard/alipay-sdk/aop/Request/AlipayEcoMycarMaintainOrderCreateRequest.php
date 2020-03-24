<?php
/**
 * ALIPAY API: alipay.eco.mycar.maintain.order.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:28:50
 */

namespace Alipay\Request;

class AlipayEcoMycarMaintainOrderCreateRequest extends AbstractAlipayRequest
{
    /**
     * 门店产品通知接口
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
