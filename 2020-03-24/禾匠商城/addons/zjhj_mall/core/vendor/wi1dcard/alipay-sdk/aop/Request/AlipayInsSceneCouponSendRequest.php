<?php
/**
 * ALIPAY API: alipay.ins.scene.coupon.send request
 *
 * @author auto create
 *
 * @since  1.0, 2017-02-23 21:17:32
 */

namespace Alipay\Request;

class AlipayInsSceneCouponSendRequest extends AbstractAlipayRequest
{
    /**
     * 保险营销权益发放接口
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
