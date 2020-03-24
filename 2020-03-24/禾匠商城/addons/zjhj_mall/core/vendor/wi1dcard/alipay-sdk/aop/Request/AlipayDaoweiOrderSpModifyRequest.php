<?php
/**
 * ALIPAY API: alipay.daowei.order.sp.modify request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 13:23:29
 */

namespace Alipay\Request;

class AlipayDaoweiOrderSpModifyRequest extends AbstractAlipayRequest
{
    /**
     * 订单服务者变更接口
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
