<?php
/**
 * ALIPAY API: zhima.merchant.borrow.entity.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 10:55:11
 */

namespace Alipay\Request;

class ZhimaMerchantBorrowEntityUploadRequest extends AbstractAlipayRequest
{
    /**
     * 信用借还借用实体数据上传接口
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
