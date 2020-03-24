<?php
/**
 * ALIPAY API: alipay.eco.mycar.maintain.data.update request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:29:17
 */

namespace Alipay\Request;

class AlipayEcoMycarMaintainDataUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 保养数据变更通知
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
