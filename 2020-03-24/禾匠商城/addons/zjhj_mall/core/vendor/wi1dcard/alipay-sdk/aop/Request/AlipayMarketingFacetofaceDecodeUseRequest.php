<?php
/**
 * ALIPAY API: alipay.marketing.facetoface.decode.use request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 11:33:58
 */

namespace Alipay\Request;

class AlipayMarketingFacetofaceDecodeUseRequest extends AbstractAlipayRequest
{
    /**
     * 开发当面付付款码解码
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
