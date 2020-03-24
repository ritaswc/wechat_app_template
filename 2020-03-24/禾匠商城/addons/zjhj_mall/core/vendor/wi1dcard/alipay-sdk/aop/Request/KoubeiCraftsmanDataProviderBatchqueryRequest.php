<?php
/**
 * ALIPAY API: koubei.craftsman.data.provider.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 20:10:21
 */

namespace Alipay\Request;

class KoubeiCraftsmanDataProviderBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 手艺人信息批量查询接口
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
