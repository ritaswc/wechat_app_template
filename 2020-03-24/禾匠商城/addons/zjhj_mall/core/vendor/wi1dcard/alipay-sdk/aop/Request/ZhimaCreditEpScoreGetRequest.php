<?php
/**
 * ALIPAY API: zhima.credit.ep.score.get request
 *
 * @author auto create
 *
 * @since  1.0, 2017-10-18 15:09:54
 */

namespace Alipay\Request;

class ZhimaCreditEpScoreGetRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻信用企业信用评分
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
