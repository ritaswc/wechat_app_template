<?php
/**
 * ALIPAY API: zhima.merchant.order.rent.complete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-02 11:47:22
 */

namespace Alipay\Request;

class ZhimaMerchantOrderRentCompleteRequest extends AbstractAlipayRequest
{
    /**
     * 信用借还订单归还
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
