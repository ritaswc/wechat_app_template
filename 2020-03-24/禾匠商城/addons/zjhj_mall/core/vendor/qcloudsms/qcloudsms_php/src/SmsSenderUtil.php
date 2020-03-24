<?php

namespace Qcloud\Sms;


/**
 * 发送Util类
 *
 */
class SmsSenderUtil
{
    /**
     * 生成随机数
     *
     * @return int 随机数结果
     */
    public function getRandom()
    {
        return rand(100000, 999999);
    }

    /**
     * 生成签名
     *
     * @param string $appid         sdkappid
     * @param string $appkey        sdkappid对应的appkey
     * @param string $curTime       当前时间
     * @param array  $phoneNumbers  手机号码
     * @return string  签名结果
     */
    public function calculateSig($appkey, $random, $curTime, $phoneNumbers)
    {
        $phoneNumbersString = $phoneNumbers[0];
        for ($i = 1; $i < count($phoneNumbers); $i++) {
            $phoneNumbersString .= ("," . $phoneNumbers[$i]);
        }

        return hash("sha256", "appkey=".$appkey."&random=".$random
            ."&time=".$curTime."&mobile=".$phoneNumbersString);
    }

    /**
     * 生成签名
     *
     * @param string $appid         sdkappid
     * @param string $appkey        sdkappid对应的appkey
     * @param string $curTime       当前时间
     * @param array  $phoneNumbers  手机号码
     * @return string  签名结果
     */
    public function calculateSigForTemplAndPhoneNumbers($appkey, $random,
        $curTime, $phoneNumbers)
    {
        $phoneNumbersString = $phoneNumbers[0];
        for ($i = 1; $i < count($phoneNumbers); $i++) {
            $phoneNumbersString .= ("," . $phoneNumbers[$i]);
        }

        return hash("sha256", "appkey=".$appkey."&random=".$random
            ."&time=".$curTime."&mobile=".$phoneNumbersString);
    }

    public function phoneNumbersToArray($nationCode, $phoneNumbers)
    {
        $i = 0;
        $tel = array();
        do {
            $telElement = new \stdClass();
            $telElement->nationcode = $nationCode;
            $telElement->mobile = $phoneNumbers[$i];
            array_push($tel, $telElement);
        } while (++$i < count($phoneNumbers));

        return $tel;
    }

    /**
     * 生成签名
     *
     * @param string $appid         sdkappid
     * @param string $appkey        sdkappid对应的appkey
     * @param string $curTime       当前时间
     * @param array  $phoneNumbers  手机号码
     * @return string  签名结果
     */
    public function calculateSigForTempl($appkey, $random, $curTime, $phoneNumber)
    {
        $phoneNumbers = array($phoneNumber);

        return $this->calculateSigForTemplAndPhoneNumbers($appkey, $random,
            $curTime, $phoneNumbers);
    }

    /**
     * 生成签名
     *
     * @param string $appid         sdkappid
     * @param string $appkey        sdkappid对应的appkey
     * @param string $curTime       当前时间
     * @return string 签名结果
     */
    public function calculateSigForPuller($appkey, $random, $curTime)
    {
        return hash("sha256", "appkey=".$appkey."&random=".$random
            ."&time=".$curTime);
    }

    /**
     * 发送请求
     *
     * @param string $url      请求地址
     * @param array  $dataObj  请求内容
     * @return string 应答json字符串
     */
    public function sendCurlPost($url, $dataObj)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataObj));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $ret = curl_exec($curl);
        if (false == $ret) {
            // curl_exec failed
            $result = "{ \"result\":" . -2 . ",\"errmsg\":\"" . curl_error($curl) . "\"}";
        } else {
            $rsp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "{ \"result\":" . -1 . ",\"errmsg\":\"". $rsp
                        . " " . curl_error($curl) ."\"}";
            } else {
                $result = $ret;
            }
        }

        curl_close($curl);

        return $result;
    }
}
