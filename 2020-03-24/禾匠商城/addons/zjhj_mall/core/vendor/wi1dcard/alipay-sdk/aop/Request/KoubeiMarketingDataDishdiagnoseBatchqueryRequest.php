<?php
/**
 * ALIPAY API: koubei.marketing.data.dishdiagnose.batchquery request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-03 14:41:41
 */

namespace Alipay\Request;

class KoubeiMarketingDataDishdiagnoseBatchqueryRequest extends AbstractAlipayRequest
{
    /**
     * 根据条件查询推荐菜
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
