<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 群发短信类
 *
 */
class SmsMultiSender
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
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/sendmultisms2";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     * 普通群发
     *
     * 普通群发需明确指定内容，如果有多个签名，请在内容中以【】的方式添加到信息内容中，
     * 否则系统将使用默认签名。
     *
     *
     * @param int    $type         短信类型，0 为普通短信，1 营销短信
     * @param string $nationCode   国家码，如 86 为中国
     * @param string $phoneNumbers 不带国家码的手机号列表
     * @param string $msg          信息内容，必须与申请的模板格式一致，否则将返回错误
     * @param string $extend       扩展码，可填空串
     * @param string $ext          服务端原样返回的参数，可填空串
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function send($type, $nationCode, $phoneNumbers, $msg, $extend = "", $ext = "")
    {
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;

        $data = new \stdClass();
        $data->tel = $this->util->phoneNumbersToArray($nationCode, $phoneNumbers);
        $data->type = $type;
        $data->msg = $msg;
        $data->sig = $this->util->calculateSig($this->appkey, $random,
            $curTime, $phoneNumbers);
        $data->time = $curTime;
        $data->extend = $extend;
        $data->ext = $ext;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 指定模板群发
     *
     *
     * @param  string $nationCode   国家码，如 86 为中国
     * @param  array  $phoneNumbers 不带国家码的手机号列表
     * @param  int    $templId      模板id
     * @param  array  $params       模板参数列表，如模板 {1}...{2}...{3}，那么需要带三个参数
     * @param  string $sign         签名，如果填空串，系统会使用默认签名
     * @param  string $extend       扩展码，可填空串
     * @param  string $ext          服务端原样返回的参数，可填空串
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function sendWithParam($nationCode, $phoneNumbers, $templId, $params,
        $sign = "", $extend = "", $ext = "")
    {
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;

        $data = new \stdClass();
        $data->tel = $this->util->phoneNumbersToArray($nationCode, $phoneNumbers);
        $data->sign = $sign;
        $data->tpl_id = $templId;
        $data->params = $params;
        $data->sig = $this->util->calculateSigForTemplAndPhoneNumbers(
            $this->appkey, $random, $curTime, $phoneNumbers);
        $data->time = $curTime;
        $data->extend = $extend;
        $data->ext = $ext;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}
