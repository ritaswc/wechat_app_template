<?php
/**
 * ALIPAY API: alipay.marketing.card.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-31 17:43:09
 */

namespace Alipay\Request;

class AlipayMarketingCardQueryRequest extends AbstractAlipayRequest
{
    /**
     * 会员卡查询
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
