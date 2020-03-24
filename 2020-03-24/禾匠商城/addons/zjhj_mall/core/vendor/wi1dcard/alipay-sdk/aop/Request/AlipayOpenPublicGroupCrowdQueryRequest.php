<?php
/**
 * ALIPAY API: alipay.open.public.group.crowd.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-11 19:06:43
 */

namespace Alipay\Request;

class AlipayOpenPublicGroupCrowdQueryRequest extends AbstractAlipayRequest
{
    /**
     * 人群数量查询
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
