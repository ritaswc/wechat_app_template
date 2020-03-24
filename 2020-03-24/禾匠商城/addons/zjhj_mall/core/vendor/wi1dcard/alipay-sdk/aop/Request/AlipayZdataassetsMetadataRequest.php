<?php
/**
 * ALIPAY API: alipay.zdataassets.metadata request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-26 15:12:06
 */

namespace Alipay\Request;

class AlipayZdataassetsMetadataRequest extends AbstractAlipayRequest
{
    /**
     * 业务参数
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
