<?php
/**
 * ALIPAY API: alipay.data.dataservice.code.reco request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-22 10:26:43
 */

namespace Alipay\Request;

class AlipayDataDataserviceCodeRecoRequest extends AbstractAlipayRequest
{
    /**
     * 改api为数立提供验证码识别服务。isv可以通过该接口，使用我们的图片识别能力。
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
