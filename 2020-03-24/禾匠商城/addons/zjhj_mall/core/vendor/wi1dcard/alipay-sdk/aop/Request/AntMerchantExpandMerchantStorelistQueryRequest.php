<?php
/**
 * ALIPAY API: ant.merchant.expand.merchant.storelist.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-07-03 13:52:35
 */

namespace Alipay\Request;

class AntMerchantExpandMerchantStorelistQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户外部门店查询接口
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
