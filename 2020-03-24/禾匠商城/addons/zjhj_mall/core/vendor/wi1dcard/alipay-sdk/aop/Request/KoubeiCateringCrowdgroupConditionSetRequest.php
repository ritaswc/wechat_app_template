<?php
/**
 * ALIPAY API: koubei.catering.crowdgroup.condition.set request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-22 14:29:19
 */

namespace Alipay\Request;

class KoubeiCateringCrowdgroupConditionSetRequest extends AbstractAlipayRequest
{
    /**
     * 口碑智慧餐厅精准营销用户人群分组操作接口
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
