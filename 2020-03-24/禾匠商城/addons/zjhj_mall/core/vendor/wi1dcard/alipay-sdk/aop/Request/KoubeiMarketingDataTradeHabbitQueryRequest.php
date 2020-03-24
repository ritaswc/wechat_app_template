<?php
/**
 * ALIPAY API: koubei.marketing.data.trade.habbit.query request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-25 17:35:38
 */

namespace Alipay\Request;

class KoubeiMarketingDataTradeHabbitQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户会员交易习惯查询接口
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
