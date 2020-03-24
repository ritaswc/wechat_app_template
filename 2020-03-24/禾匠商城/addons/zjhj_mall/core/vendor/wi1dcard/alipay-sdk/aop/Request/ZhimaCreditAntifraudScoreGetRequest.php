<?php
/**
 * ALIPAY API: zhima.credit.antifraud.score.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-30 10:55:35
 */

namespace Alipay\Request;

class ZhimaCreditAntifraudScoreGetRequest extends AbstractAlipayRequest
{
    /**
     * 申请欺诈评分
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
