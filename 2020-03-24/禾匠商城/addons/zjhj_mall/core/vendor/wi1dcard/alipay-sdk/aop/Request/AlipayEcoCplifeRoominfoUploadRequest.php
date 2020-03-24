<?php
/**
 * ALIPAY API: alipay.eco.cplife.roominfo.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-09 20:16:50
 */

namespace Alipay\Request;

class AlipayEcoCplifeRoominfoUploadRequest extends AbstractAlipayRequest
{
    /**
     * 上传物业小区内部房屋信息.
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
