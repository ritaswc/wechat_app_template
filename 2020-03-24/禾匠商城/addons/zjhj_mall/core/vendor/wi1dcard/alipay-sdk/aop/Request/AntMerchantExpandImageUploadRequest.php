<?php
/**
 * ALIPAY API: ant.merchant.expand.image.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-23 11:30:21
 */

namespace Alipay\Request;

class AntMerchantExpandImageUploadRequest extends AbstractAlipayRequest
{
    /**
     * 图片二进制字节流
     **/
    private $imageContent;
    /**
     * 图片格式
     **/
    private $imageType;

    public function setImageContent($imageContent)
    {
        $this->imageContent = $imageContent;
        $this->apiParams['image_content'] = $imageContent;
    }

    public function getImageContent()
    {
        return $this->imageContent;
    }

    public function setImageType($imageType)
    {
        $this->imageType = $imageType;
        $this->apiParams['image_type'] = $imageType;
    }

    public function getImageType()
    {
        return $this->imageType;
    }
}
