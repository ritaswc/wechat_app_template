<?php
/**
 * ALIPAY API: alipay.commerce.lottery.present.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-30 14:30:00
 */

namespace Alipay\Request;

class AlipayCommerceLotteryPresentSendRequest extends AbstractAlipayRequest
{
    /**
     * 商家给用户赠送彩票，由亚博科技提供服务
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
