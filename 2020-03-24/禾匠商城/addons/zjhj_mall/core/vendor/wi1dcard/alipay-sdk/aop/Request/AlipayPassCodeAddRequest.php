<?php
/**
 * ALIPAY API: alipay.pass.code.add request
 *
 * @author auto create
 *
 * @since  1.0, 2014-06-12 17:16:12
 */

namespace Alipay\Request;

class AlipayPassCodeAddRequest extends AbstractAlipayRequest
{
    /**
     * alipass文件Base64编码后的内容。
     **/
    private $fileContent;
    /**
     * 识别信息
     * 当 recognition_type=1时， recognition_info={“partner_id”:”2088102114633762”,“out_trade_no”:”1234567”}
     * 当recognition_type=2时， recognition_info={“user_id”:”2088102114633761“ }
     **/
    private $recognitionInfo;
    /**
     * 发放对象识别类型
     * 1-  订单信息
     * 2-  支付宝userId
     **/
    private $recognitionType;
    /**
     * 该pass的核销方式,如果为空，则默认为["wave","qrcode"]
     **/
    private $verifyType;

    public function setFileContent($fileContent)
    {
        $this->fileContent = $fileContent;
        $this->apiParams['file_content'] = $fileContent;
    }

    public function getFileContent()
    {
        return $this->fileContent;
    }

    public function setRecognitionInfo($recognitionInfo)
    {
        $this->recognitionInfo = $recognitionInfo;
        $this->apiParams['recognition_info'] = $recognitionInfo;
    }

    public function getRecognitionInfo()
    {
        return $this->recognitionInfo;
    }

    public function setRecognitionType($recognitionType)
    {
        $this->recognitionType = $recognitionType;
        $this->apiParams['recognition_type'] = $recognitionType;
    }

    public function getRecognitionType()
    {
        return $this->recognitionType;
    }

    public function setVerifyType($verifyType)
    {
        $this->verifyType = $verifyType;
        $this->apiParams['verify_type'] = $verifyType;
    }

    public function getVerifyType()
    {
        return $this->verifyType;
    }
}
