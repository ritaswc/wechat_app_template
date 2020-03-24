<?php
/**
 * ALIPAY API: alipay.daowei.order.confirm request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 13:23:44
 */

namespace Alipay\Request;

class AlipayDaoweiOrderConfirmRequest extends AbstractAlipayRequest
{
    /**
     * 订单确认接口
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
