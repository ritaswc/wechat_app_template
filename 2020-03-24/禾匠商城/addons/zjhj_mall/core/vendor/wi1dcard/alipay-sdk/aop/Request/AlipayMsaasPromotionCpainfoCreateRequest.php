<?php
/**
 * ALIPAY API: alipay.msaas.promotion.cpainfo.create request
 *
 * @author auto create
 *
 * @since  1.0, 2016-03-04 10:56:52
 */

namespace Alipay\Request;

class AlipayMsaasPromotionCpainfoCreateRequest extends AbstractAlipayRequest
{
    /**
     * 此api为了让第三方渠道端调用 ，记录他们的调用数据，然后根据数据比对进行计费功能
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
