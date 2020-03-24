<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.function.query request
 *
 * @author auto create
 *
 * @since  1.0, 2015-12-15 11:19:03
 */

namespace Alipay\Request;

class AlipayCommerceCityfacilitatorFunctionQueryRequest extends AbstractAlipayRequest
{
    /**
     * 基于设备和城市查询当前支持的功能
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
