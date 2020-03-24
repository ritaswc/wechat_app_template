<?php
/**
 * ALIPAY API: alipay.marketing.userule.pid.query request
 *
 * @author auto create
 *
 * @since  1.0, 2017-07-25 16:18:09
 */

namespace Alipay\Request;

class AlipayMarketingUserulePidQueryRequest extends AbstractAlipayRequest
{
    /**
     * 商户签约PID查询
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
