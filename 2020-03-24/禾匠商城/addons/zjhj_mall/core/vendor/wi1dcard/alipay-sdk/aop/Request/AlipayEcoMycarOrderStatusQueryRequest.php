<?php
/**
 * ALIPAY API: alipay.eco.mycar.order.status.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:09
 */

namespace Alipay\Request;

class AlipayEcoMycarOrderStatusQueryRequest extends AbstractAlipayRequest
{
    /**
     * 停车卡查询收费信息
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
