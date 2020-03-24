<?php
/**
 * ALIPAY API: zhima.merchant.single.data.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-09 15:57:14
 */

namespace Alipay\Request;

class ZhimaMerchantSingleDataUploadRequest extends AbstractAlipayRequest
{
    /**
     * 单条数据传入
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
