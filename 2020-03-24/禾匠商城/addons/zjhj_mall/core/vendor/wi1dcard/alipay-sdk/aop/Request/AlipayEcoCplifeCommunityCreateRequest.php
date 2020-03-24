<?php
/**
 * ALIPAY API: alipay.eco.cplife.community.create request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:16:18
 */

namespace Alipay\Request;

class AlipayEcoCplifeCommunityCreateRequest extends AbstractAlipayRequest
{
    /**
     * 创建物业小区
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
