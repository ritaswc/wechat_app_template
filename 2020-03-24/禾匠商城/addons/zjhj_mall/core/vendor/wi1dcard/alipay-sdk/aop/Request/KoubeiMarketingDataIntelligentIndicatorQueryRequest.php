<?php
/**
 * ALIPAY API: koubei.marketing.data.intelligent.indicator.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-17 06:02:13
 */

namespace Alipay\Request;

class KoubeiMarketingDataIntelligentIndicatorQueryRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销方案维度的数据查询
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
