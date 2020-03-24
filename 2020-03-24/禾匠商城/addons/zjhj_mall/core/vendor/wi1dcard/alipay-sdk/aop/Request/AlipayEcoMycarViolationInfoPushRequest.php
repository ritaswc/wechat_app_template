<?php
/**
 * ALIPAY API: alipay.eco.mycar.violation.info.push request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:47
 */

namespace Alipay\Request;

class AlipayEcoMycarViolationInfoPushRequest extends AbstractAlipayRequest
{
    /**
     * ISV推送新违章信息
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
