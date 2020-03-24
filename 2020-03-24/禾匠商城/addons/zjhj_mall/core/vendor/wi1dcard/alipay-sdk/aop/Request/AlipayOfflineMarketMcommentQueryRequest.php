<?php
/**
 * ALIPAY API: alipay.offline.market.mcomment.query request
 *
 * @author auto create
 *
 * @since  1.0, 2016-06-13 20:25:02
 */

namespace Alipay\Request;

class AlipayOfflineMarketMcommentQueryRequest extends AbstractAlipayRequest
{
    /**
     * 查询交易评价信息
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
