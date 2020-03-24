<?php
/**
 * ALIPAY API: alipay.commerce.lottery.presentlist.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-30 15:00:00
 */

namespace Alipay\Request;

class AlipayCommerceLotteryPresentlistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询调用者指定时间范围内的彩票赠送列表，由亚博科技提供服务
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
