<?php
/**
 * ALIPAY API: zhima.open.app.keyan.lql.create request
 *
 * @author auto create
 *
 * @since  1.0, 2017-06-28 11:41:24
 */

namespace Alipay\Request;

class ZhimaOpenAppKeyanLqlCreateRequest extends AbstractAlipayRequest
{
    /**
     * keyantestoneone
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
