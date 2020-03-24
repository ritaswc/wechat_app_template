<?php
/**
 * ALIPAY API: ant.merchant.expand.contract.facetoface.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-08 19:36:40
 */

namespace Alipay\Request;

class AntMerchantExpandContractFacetofaceQueryRequest extends AbstractAlipayRequest
{
    /**
     * 当面付申请查询接口
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
