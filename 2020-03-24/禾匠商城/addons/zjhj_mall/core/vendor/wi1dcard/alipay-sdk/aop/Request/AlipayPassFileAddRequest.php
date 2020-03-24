<?php
/**
 * ALIPAY API: alipay.pass.file.add request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 20:13:48
 */

namespace Alipay\Request;

class AlipayPassFileAddRequest extends AbstractAlipayRequest
{
    /**
     * 支付宝pass文件二进制Base64加密字符串
     **/
    private $fileContent;
    /**
     * 支付宝用户识别信息：
     * 当 recognition_type=1时， recognition_info={“partner_id”:”2088102114633762”,“out_trade_no”:”1234567”}；
     * 当recognition_type=2时， recognition_info={“user_id”:”2088102114633761“}
     * 当recognition_type=3时，recognition_info={“mobile”:”136XXXXXXXX“}
     **/
    private $recognitionInfo;
    /**
     * Alipass添加对象识别类型【1--订单信息；2--支付宝userId;3--支付宝绑定手机号】
     **/
    private $recognitionType;

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
}
