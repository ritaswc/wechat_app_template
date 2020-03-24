<?php
/**
 * ALIPAY API: zhima.credit.watchlist.brief.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-11-23 14:20:59
 */

namespace Alipay\Request;

class ZhimaCreditWatchlistBriefGetRequest extends AbstractAlipayRequest
{
    /**
     * 行业关注名单普惠版
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
