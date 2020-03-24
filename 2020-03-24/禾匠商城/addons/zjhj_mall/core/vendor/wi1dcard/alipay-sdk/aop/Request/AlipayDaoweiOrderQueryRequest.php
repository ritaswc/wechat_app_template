<?php
/**
 * ALIPAY API: alipay.daowei.order.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 13:23:59
 */

namespace Alipay\Request;

class AlipayDaoweiOrderQueryRequest extends AbstractAlipayRequest
{
    /**
     * 到位订单查询接口
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
