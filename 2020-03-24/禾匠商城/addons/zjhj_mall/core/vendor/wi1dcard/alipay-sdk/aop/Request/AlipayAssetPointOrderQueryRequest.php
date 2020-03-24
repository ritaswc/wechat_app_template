<?php
/**
 * ALIPAY API: alipay.asset.point.order.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 19:02:42
 */

namespace Alipay\Request;

class AlipayAssetPointOrderQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户在调用集分宝发放接口后可以通过此接口查询发放情况
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
