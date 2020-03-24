<?php
/**
 * ALIPAY API: zoloz.identification.user.web.initialize request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-05 11:31:47
 */

namespace Alipay\Request;

class ZolozIdentificationUserWebInitializeRequest extends AbstractAlipayRequest
{
    /**
     * H5刷脸认证初始化
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
