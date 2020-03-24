<?php
/**
 * ALIPAY API: alipay.eco.cplife.notice.publish request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:18:37
 */

namespace Alipay\Request;

class AlipayEcoCplifeNoticePublishRequest extends AbstractAlipayRequest
{
    /**
     * 发布物业通知公告
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
