<?php
/**
 * ALIPAY API: alipay.pass.tpl.content.update request
 *
 * @author auto create
 *
 * @since  1.0, 2017-12-07 16:33:36
 */

namespace Alipay\Request;

class AlipayPassTplContentUpdateRequest extends AbstractAlipayRequest
{
    /**
     * 代理商代替商户发放卡券后，再代替商户更新卡券时，此值为商户的pid/appid
     **/
    private $channelId;
    /**
     * 支付宝pass唯一标识
     **/
    private $serialNumber;
    /**
     * 券状态,支持更新为USED,CLOSED两种状态
     **/
    private $status;
    /**
     * 模版动态参数信息【支付宝pass模版参数键值对JSON字符串】
     **/
    private $tplParams;
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

    public function setTplParams($tplParams)
    {
        $this->tplParams = $tplParams;
        $this->apiParams['tpl_params'] = $tplParams;
    }

    public function getTplParams()
    {
        return $this->tplParams;
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
