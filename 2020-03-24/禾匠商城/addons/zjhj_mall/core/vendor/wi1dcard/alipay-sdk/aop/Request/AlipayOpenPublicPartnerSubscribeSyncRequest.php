<?php
/**
 * ALIPAY API: alipay.open.public.partner.subscribe.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 20:24:36
 */

namespace Alipay\Request;

class AlipayOpenPublicPartnerSubscribeSyncRequest extends AbstractAlipayRequest
{
    /**
     * 用于为服务窗合作伙伴（如YunOS），提供订阅关系（关注与取消关注）同步功能
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
