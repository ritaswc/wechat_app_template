<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 发送语音验证码类
 *
 */
class SmsVoiceVerifyCodeSender
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    /**
     * 构造函数
     *
     * @param string $appid  sdkappid
     * @param string $appkey sdkappid对应的appkey
     */
    public function __construct($appid, $appkey)
    {
        $this->url = "https://yun.tim.qq.com/v5/tlsvoicesvr/sendvoice";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     * 发送语音验证码
     *
     * @param string $nationCode  国家码，如 86 为中国
     * @param string $phoneNumber 不带国家码的手机号
     * @param string $msg         信息内容，必须与申请的模板格式一致，否则将返回错误
     * @param int    $playtimes   播放次数，可选，最多3次，默认2次
     * @param string $ext         用户的session内容，服务端原样返回，可选字段，不需要可填空串
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function send($nationCode, $phoneNumber, $msg, $playtimes = 2, $ext = "")
    {
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;

        // 按照协议组织 post 包体
        $data = new \stdClass();
        $tel = new \stdClass();
        $tel->nationcode = "".$nationCode;
        $tel->mobile = "".$phoneNumber;

        $data->tel = $tel;
        $data->msg = $msg;
        $data->playtimes = $playtimes;
        // app凭证
        $data->sig = hash("sha256",
            "appkey=".$this->appkey."&random=".$random."&time="
            .$curTime."&mobile=".$phoneNumber, FALSE);
        // unix时间戳，请求发起时间，如果和系统时间相差超过10分钟则会返回失败
        $data->time = $curTime;
        $data->ext = $ext;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}
