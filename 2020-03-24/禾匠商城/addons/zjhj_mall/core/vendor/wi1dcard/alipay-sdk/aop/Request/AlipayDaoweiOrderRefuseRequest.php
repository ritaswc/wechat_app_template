<?php
/**
 * ALIPAY API: alipay.daowei.order.refuse request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 13:24:12
 */

namespace Alipay\Request;

class AlipayDaoweiOrderRefuseRequest extends AbstractAlipayRequest
{
    /**
     * 到位的单笔订单拒绝接口
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
