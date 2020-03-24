<?php
/**
 * ALIPAY API: koubei.catering.commodity.order.buy request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-23 14:21:37
 */

namespace Alipay\Request;

class KoubeiCateringCommodityOrderBuyRequest extends AbstractAlipayRequest
{
    /**
     * isv订购口碑插件接口
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
