<?php
/**
 * ALIPAY API: alipay.pass.sync.update request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 20:19:03
 */

namespace Alipay\Request;

class AlipayPassSyncUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 代理商代替商户发放卡券后，再代替商户更新卡券时，此值为商户的pid/appid；商户自己发券时，此值为空或者商户appId
     **/
    private $channelId;
    /**
     * 用来传递外部交易号等扩展参数信息，格式为json
     **/
    private $extInfo;
    /**
     * 需要修改的pass信息，可以更新全部pass信息，也可以斤更新某一节点。pass信息中的pass.json中的数据格式，如果不需要更新该属性值，设置为null即可。
     **/
    private $pass;
    /**
     * Alipass唯一标识
     **/
    private $serialNumber;
    /**
     * Alipass状态，目前仅支持CLOSED及USED两种数据。status为USED时，verify_type即为核销时的核销方式。
     **/
    private $status;
    /**
     * 核销码串值【当状态变更为USED时，建议传入】
     **/
    private $verifyCode;
    /**
     * 核销方式，目前支持：wave（声波方式）、qrcode（二维码方式）、barcode（条码方式）、input（文本方式，即手工输入方式）。pass和verify_type不能同时为空
     **/
    private $verifyType;

    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;
        $this->apiParams['channel_id'] = $channelId;
    }

    public function getChannelId()
    {
        return $this->channelId;
    }

    public function setExtInfo($extInfo)
    {
        $this->extInfo = $extInfo;
        $this->apiParams['ext_info'] = $extInfo;
    }

    public function getExtInfo()
    {
        return $this->extInfo;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
        $this->apiParams['pass'] = $pass;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
        $this->apiParams['serial_number'] = $serialNumber;
    }

    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->apiParams['status'] = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setVerifyCode($verifyCode)
    {
        $this->verifyCode = $verifyCode;
        $this->apiParams['verify_code'] = $verifyCode;
    }

    public function getVerifyCode()
    {
        return $this->verifyCode;
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
