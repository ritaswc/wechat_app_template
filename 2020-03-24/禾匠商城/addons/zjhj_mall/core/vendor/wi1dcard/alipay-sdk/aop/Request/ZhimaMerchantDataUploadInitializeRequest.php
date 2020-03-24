<?php
/**
 * ALIPAY API: zhima.merchant.data.upload.initialize request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-08 13:55:00
 */

namespace Alipay\Request;

class ZhimaMerchantDataUploadInitializeRequest extends AbstractAlipayRequest
{
    /**
     * 芝麻数据传入初始化
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
