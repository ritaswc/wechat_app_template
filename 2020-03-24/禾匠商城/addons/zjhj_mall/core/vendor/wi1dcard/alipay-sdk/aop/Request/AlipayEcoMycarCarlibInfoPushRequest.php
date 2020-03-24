<?php
/**
 * ALIPAY API: alipay.eco.mycar.carlib.info.push request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:30:04
 */

namespace Alipay\Request;

class AlipayEcoMycarCarlibInfoPushRequest extends AbstractAlipayRequest
{
    /**
     * 同步车型库
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
