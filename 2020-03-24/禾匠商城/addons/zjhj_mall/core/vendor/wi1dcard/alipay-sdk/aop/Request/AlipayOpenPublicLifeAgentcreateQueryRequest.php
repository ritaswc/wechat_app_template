<?php
/**
 * ALIPAY API: alipay.open.public.life.agentcreate.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-10 11:16:11
 */

namespace Alipay\Request;

class AlipayOpenPublicLifeAgentcreateQueryRequest extends AbstractAlipayRequest
{
    /**
     * isv代创建生活号申请状态查询接口
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
