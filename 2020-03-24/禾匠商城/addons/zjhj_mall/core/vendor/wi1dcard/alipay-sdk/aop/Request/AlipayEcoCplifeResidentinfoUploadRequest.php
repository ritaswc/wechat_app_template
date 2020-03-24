<?php
/**
 * ALIPAY API: alipay.eco.cplife.residentinfo.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:19:02
 */

namespace Alipay\Request;

class AlipayEcoCplifeResidentinfoUploadRequest extends AbstractAlipayRequest
{
    /**
     * 物业小区业主信息上传
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
