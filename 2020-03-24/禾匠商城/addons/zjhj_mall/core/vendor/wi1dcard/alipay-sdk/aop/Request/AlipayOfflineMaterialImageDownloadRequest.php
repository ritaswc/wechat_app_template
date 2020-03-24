<?php
/**
 * ALIPAY API: alipay.offline.material.image.download request
 *
 * @author auto create
 *
 * @since  1.0, 2017-04-14 11:37:33
 */

namespace Alipay\Request;

class AlipayOfflineMaterialImageDownloadRequest extends AbstractAlipayRequest
{
    /**
     * 图片id列表
     **/
    private $imageIds;

    public function setImageIds($imageIds)
    {
        $this->imageIds = $imageIds;
        $this->apiParams['image_ids'] = $imageIds;
    }

    public function getImageIds()
    {
        return $this->imageIds;
    }
}
