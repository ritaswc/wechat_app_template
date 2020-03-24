<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 发送语音通知类
 *
 */
class SmsVoicePromptSender
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
        $this->url = "https://yun.tim.qq.com/v5/tlsvoicesvr/sendvoiceprompt";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     *
     * 发送语音通知
     *
     * @param string $nationCode  国家码，如 86 为中国
     * @param string $phoneNumber 不带国家码的手机号
     * @param string $prompttype  语音类型，目前固定为2
     * @param string $msg         信息内容，必须与申请的模板格式一致，否则将返回错误
     * @param string $playtimes   播放次数，可选，最多3次，默认2次
     * @param string $ext         用户的session内容，服务端原样返回，可选字段，不需要可填空串
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function send($nationCode, $phoneNumber, $prompttype, $msg, $playtimes = 2, $ext = "")
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
        // 通知内容，utf8编码，支持中文英文、数字及组合，需要和语音内容模版相匹配
        $data->promptfile = $msg;
        // 固定值 2
        $data->prompttype = $prompttype;
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
