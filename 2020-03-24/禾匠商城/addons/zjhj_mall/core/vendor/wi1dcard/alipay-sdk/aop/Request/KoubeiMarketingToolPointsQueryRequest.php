<?php
/**
 * ALIPAY API: koubei.marketing.tool.points.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-10-09 11:45:18
 */

namespace Alipay\Request;

class KoubeiMarketingToolPointsQueryRequest extends AbstractAlipayRequest
{
    /**
     * 集点查询
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
