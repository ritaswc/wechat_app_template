<?php
/**
 * ALIPAY API: alipay.eco.cplife.notice.delete request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:18:24
 */

namespace Alipay\Request;

class AlipayEcoCplifeNoticeDeleteRequest extends AbstractAlipayRequest
{
    /**
     * 删除物业社区通知通告
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
