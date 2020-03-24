<?php
/**
 * ALIPAY API: ant.merchant.expand.contract.facetoface.sign request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-08 19:38:04
 */

namespace Alipay\Request;

class AntMerchantExpandContractFacetofaceSignRequest extends AbstractAlipayRequest
{
    /**
     * 开通当面付申请接口
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
