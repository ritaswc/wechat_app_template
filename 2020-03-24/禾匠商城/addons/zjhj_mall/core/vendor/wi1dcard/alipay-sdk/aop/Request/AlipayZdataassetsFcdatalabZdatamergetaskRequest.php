<?php
/**
 * ALIPAY API: alipay.zdataassets.fcdatalab.zdatamergetask request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-26 15:14:28
 */

namespace Alipay\Request;

class AlipayZdataassetsFcdatalabZdatamergetaskRequest extends AbstractAlipayRequest
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
