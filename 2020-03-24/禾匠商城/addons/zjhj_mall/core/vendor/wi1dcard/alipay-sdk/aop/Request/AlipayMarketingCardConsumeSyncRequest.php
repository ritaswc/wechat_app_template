<?php
/**
 * ALIPAY API: alipay.marketing.card.consume.sync request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-23 14:51:37
 */

namespace Alipay\Request;

class AlipayMarketingCardConsumeSyncRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡消费记录同步
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
