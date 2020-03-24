<?php
/**
 * ALIPAY API: alipay.flashsales.stock.sync.update request
 *
 * @author auto create
 *
 * @since  1.0, 2014-08-22 15:32:32
 */

namespace Alipay\Request;

class AlipayFlashsalesStockSyncUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 商户的商品id
     **/
    private $outProductId;
    /**
     * 服务窗id
     **/
    private $publicId;
    /**
     * 库存数量
     **/
    private $stock;

    public function setOutProductId($outProductId)
    {
        $this->outProductId = $outProductId;
        $this->apiParams['out_product_id'] = $outProductId;
    }

    public function getOutProductId()
    {
        return $this->outProductId;
    }

    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
        $this->apiParams['public_id'] = $publicId;
    }

    public function getPublicId()
    {
        return $this->publicId;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
        $this->apiParams['stock'] = $stock;
    }

    public function getStock()
    {
        return $this->stock;
    }
}
