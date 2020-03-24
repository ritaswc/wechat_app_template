<?php
/**
 * ALIPAY API: koubei.marketing.tool.points.update request
 *
 * @author auto create
 *
 * @since  1.0, 2016-11-15 18:49:47
 */

namespace Alipay\Request;

class KoubeiMarketingToolPointsUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 更新卡积分
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
