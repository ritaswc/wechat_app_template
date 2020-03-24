<?php
/**
 * ALIPAY API: alipay.eco.mycar.maintain.orderstatus.update request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:02
 */

namespace Alipay\Request;

class AlipayEcoMycarMaintainOrderstatusUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 保养订单状态更新
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
