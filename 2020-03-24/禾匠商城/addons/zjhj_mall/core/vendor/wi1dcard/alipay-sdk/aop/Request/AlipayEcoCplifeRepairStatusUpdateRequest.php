<?php
/**
 * ALIPAY API: alipay.eco.cplife.repair.status.update request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:19:57
 */

namespace Alipay\Request;

class AlipayEcoCplifeRepairStatusUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 社区物业报事报修单状态更新
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
