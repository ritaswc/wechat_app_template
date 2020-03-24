<?php
/**
 * ALIPAY API: alipay.offline.marketing.voucher.code.upload request
 *
 * @author auto create
 *
 * @since  1.0, 2018-01-12 10:57:49
 */

namespace Alipay\Request;

class AlipayOfflineMarketingVoucherCodeUploadRequest extends AbstractAlipayRequest
{
    /**
     * 约定的扩展参数
     **/
    private $extendParams;
    /**
     * 文件编码
     **/
    private $fileCharset;
    /**
     * 文件二进制内容
     **/
    private $fileContent;

    public function setExtendParams($extendParams)
    {
        $this->extendParams = $extendParams;
        $this->apiParams['extend_params'] = $extendParams;
    }

    public function getExtendParams()
    {
        return $this->extendParams;
    }

    public function setFileCharset($fileCharset)
    {
        $this->fileCharset = $fileCharset;
        $this->apiParams['file_charset'] = $fileCharset;
    }

    public function getFileCharset()
    {
        return $this->fileCharset;
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
