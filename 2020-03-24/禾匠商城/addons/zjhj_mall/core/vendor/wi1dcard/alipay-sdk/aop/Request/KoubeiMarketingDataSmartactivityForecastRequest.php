<?php
/**
 * ALIPAY API: koubei.marketing.data.smartactivity.forecast request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:34:33
 */

namespace Alipay\Request;

class KoubeiMarketingDataSmartactivityForecastRequest extends AbstractAlipayRequest
{
    /**
     * 商户智能活动效果预测接口
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
