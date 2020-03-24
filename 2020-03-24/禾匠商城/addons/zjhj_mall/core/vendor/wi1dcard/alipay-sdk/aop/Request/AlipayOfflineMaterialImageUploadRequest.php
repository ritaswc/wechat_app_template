<?php
/**
 * ALIPAY API: alipay.offline.material.image.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-05-09 13:39:20
 */

namespace Alipay\Request;

class AlipayOfflineMaterialImageUploadRequest extends AbstractAlipayRequest
{
    /**
     * 图片/视频二进制内容，图片/视频大小不能超过5M
     **/
    private $imageContent;
    /**
     * 图片/视频名称
     **/
    private $imageName;
    /**
     * 用于显示指定图片/视频所属的partnerId（支付宝内部使用，外部商户无需填写此字段）
     **/
    private $imagePid;
    /**
     * 图片/视频格式
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

    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
        $this->apiParams['image_name'] = $imageName;
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImagePid($imagePid)
    {
        $this->imagePid = $imagePid;
        $this->apiParams['image_pid'] = $imagePid;
    }

    public function getImagePid()
    {
        return $this->imagePid;
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
