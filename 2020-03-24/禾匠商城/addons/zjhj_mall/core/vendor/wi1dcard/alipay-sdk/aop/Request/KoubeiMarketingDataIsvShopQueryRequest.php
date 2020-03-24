<?php
/**
 * ALIPAY API: koubei.marketing.data.isv.shop.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-26 09:48:54
 */

namespace Alipay\Request;

class KoubeiMarketingDataIsvShopQueryRequest extends AbstractAlipayRequest
{
    /**
     * ISV商户门店摘要信息批量查询接口
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
