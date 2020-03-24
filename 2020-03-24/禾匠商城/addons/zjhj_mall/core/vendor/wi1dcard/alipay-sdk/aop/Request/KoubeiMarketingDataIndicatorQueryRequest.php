<?php
/**
 * ALIPAY API: koubei.marketing.data.indicator.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-11 19:50:04
 */

namespace Alipay\Request;

class KoubeiMarketingDataIndicatorQueryRequest extends AbstractAlipayRequest
{
    /**
     * 营销活动指标查询
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
