<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 拉取单个手机短信状态类
 *
 */
class SmsMobileStatusPuller
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
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/pullstatus4mobile";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     * 拉取回执结果
     *
     * @param int    $type         拉取类型，0表示回执结果，1表示回复信息
     * @param string $nationCode   国家码，如 86 为中国
     * @param string $mobile       不带国家码的手机号
     * @param int    $beginTime    开始时间(unix timestamp)
     * @param int    $endTime      结束时间(unix timestamp)
     * @param int    $max          拉取最大条数，最多100
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    private function pull($type, $nationCode, $mobile, $beginTime, $endTime, $max)
    {
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;

        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->type = $type;
        $data->max = $max;
        $data->begin_time = $beginTime;
        $data->end_time = $endTime;
        $data->nationcode = $nationCode;
        $data->mobile = $mobile;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 拉取回执结果
     *
     * @param string $nationCode   国家码，如 86 为中国
     * @param string $mobile       不带国家码的手机号
     * @param int    $beginTime    开始时间(unix timestamp)
     * @param int    $endTime      结束时间(unix timestamp)
     * @param int    $max          拉取最大条数，最多100
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function pullCallback($nationCode, $mobile, $beginTime, $endTime, $max)
    {
        return $this->pull(0, $nationCode, $mobile, $beginTime, $endTime, $max);
    }

    /**
     * 拉取回复信息
     *
     * @param string $nationCode   国家码，如 86 为中国
     * @param string $mobile       不带国家码的手机号
     * @param int    $beginTime    开始时间(unix timestamp)
     * @param int    $endTime      结束时间(unix timestamp)
     * @param int    $max          拉取最大条数，最多100
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function pullReply($nationCode, $mobile, $beginTime, $endTime, $max)
    {
        return $this->pull(1, $nationCode, $mobile, $beginTime, $endTime, $max);
    }
}