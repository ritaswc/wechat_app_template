<?php
/**
 * ALIPAY API: ant.merchant.expand.enterprise.apply request
 *
 * @author auto create
 *
 * @since  1.0, 2016-09-26 13:19:59
 */

namespace Alipay\Request;

class AntMerchantExpandEnterpriseApplyRequest extends AbstractAlipayRequest
{
    /**
     * 商户入驻接口
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
