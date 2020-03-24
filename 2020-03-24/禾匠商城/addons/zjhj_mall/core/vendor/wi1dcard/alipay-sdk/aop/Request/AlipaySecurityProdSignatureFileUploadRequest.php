<?php
/**
 * ALIPAY API: alipay.security.prod.signature.file.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-20 15:24:53
 */

namespace Alipay\Request;

class AlipaySecurityProdSignatureFileUploadRequest extends AbstractAlipayRequest
{
    /**
     * 业务唯一标识，由支付宝统一分配，无法自助获取
     **/
    private $bizProduct;
    /**
     * 传入上传的文件流
     **/
    private $fileContent;

    public function setBizProduct($bizProduct)
    {
        $this->bizProduct = $bizProduct;
        $this->apiParams['biz_product'] = $bizProduct;
    }

    public function getBizProduct()
    {
        return $this->bizProduct;
    }

    public function setFileContent($fileContent)
    {
        $this->fileContent = $fileContent;
        $this->apiParams['file_content'] = $fileContent;
    }

    public function getFileContent()
    {
        return $this->fileContent;
    }
}
