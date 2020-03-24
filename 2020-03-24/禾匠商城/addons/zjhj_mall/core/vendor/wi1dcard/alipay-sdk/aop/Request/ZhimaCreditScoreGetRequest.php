<?php
/**
 * ALIPAY API: zhima.credit.score.get request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-14 21:31:44
 */

namespace Alipay\Request;

class ZhimaCreditScoreGetRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻信用评分
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
