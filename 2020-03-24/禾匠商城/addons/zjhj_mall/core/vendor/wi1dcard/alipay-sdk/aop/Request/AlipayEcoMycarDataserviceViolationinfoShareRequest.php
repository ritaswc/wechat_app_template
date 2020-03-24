<?php
/**
 * ALIPAY API: alipay.eco.mycar.dataservice.violationinfo.share request
 *
 * @author auto create
 *
 * @since  1.0, 2017-09-15 16:30:19
 */

namespace Alipay\Request;

class AlipayEcoMycarDataserviceViolationinfoShareRequest extends AbstractAlipayRequest
{
    /**
     * ISV获取违章车辆信息
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
