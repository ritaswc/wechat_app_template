<?php
/**
 * ALIPAY API: alipay.zdataassets.easyservice request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-26 15:11:21
 */

namespace Alipay\Request;

class AlipayZdataassetsEasyserviceRequest extends AbstractAlipayRequest
{
    /**
     * biz_content
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
