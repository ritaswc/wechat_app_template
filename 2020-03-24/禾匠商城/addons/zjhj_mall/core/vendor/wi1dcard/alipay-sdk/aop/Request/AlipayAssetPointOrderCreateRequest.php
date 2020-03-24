<?php
/**
 * ALIPAY API: alipay.asset.point.order.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 18:53:05
 */

namespace Alipay\Request;

class AlipayAssetPointOrderCreateRequest extends AbstractAlipayRequest
{
    /**
     * 商户在采购完集分宝后可以通过此接口发放集分宝
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
