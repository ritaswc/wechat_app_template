<?php
/**
 * ALIPAY API: koubei.marketing.data.intelligent.effect.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-17 06:02:50
 */

namespace Alipay\Request;

class KoubeiMarketingDataIntelligentEffectQueryRequest extends AbstractAlipayRequest
{
    /**
     * 智能营销活动效果预测
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
