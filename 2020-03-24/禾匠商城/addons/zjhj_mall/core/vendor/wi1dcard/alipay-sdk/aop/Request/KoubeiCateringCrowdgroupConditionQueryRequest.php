<?php
/**
 * ALIPAY API: koubei.catering.crowdgroup.condition.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-22 14:35:00
 */

namespace Alipay\Request;

class KoubeiCateringCrowdgroupConditionQueryRequest extends AbstractAlipayRequest
{
    /**
     * isv创建用户规则查询
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
