<?php
/**
 * ALIPAY API: alipay.eco.cplife.useridentity.status.update request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:20:09
 */

namespace Alipay\Request;

class AlipayEcoCplifeUseridentityStatusUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 社区物业业主鉴权状态更新
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
