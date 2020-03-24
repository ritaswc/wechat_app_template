<?php
/**
 * ALIPAY API: alipay.open.mini.version.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-03-14 14:01:35
 */

namespace Alipay\Request;

class AlipayOpenMiniVersionUploadRequest extends AbstractAlipayRequest
{
    /**
     * 小程序基于模板上传版本
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
