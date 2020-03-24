<?php
/**
 * ALIPAY API: zhima.credit.score.brief.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-14 21:20:31
 */

namespace Alipay\Request;

class ZhimaCreditScoreBriefGetRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻信用评分普惠版
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
