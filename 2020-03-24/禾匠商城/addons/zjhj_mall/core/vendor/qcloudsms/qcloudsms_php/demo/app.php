<?php

require __DIR__ . "/vendor/autoload.php";

use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsVoiceVerifyCodeSender;
use Qcloud\Sms\SmsVoicePromptSender;
use Qcloud\Sms\SmsStatusPuller;
use Qcloud\Sms\SmsMobileStatusPuller;


// 短信应用SDK AppID
$appid = 1400009099; // 1400开头

// 短信应用SDK AppKey
$appkey = "9ff91d87c2cd7cd0ea762f141975d1df37481d48700d70ac37470aefc60f9bad";

// 需要发送短信的手机号码
$phoneNumbers = ["21212313123", "12345678902", "12345678903"];

// 短信模板ID，需要在短信应用中申请
$templateId = 7839;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

// 签名
$smsSign = "腾讯云"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`

// 单发短信
try {
    $ssender = new SmsSingleSender($appid, $appkey);
    $result = $ssender->send(0, "86", $phoneNumber[0],
        "【腾讯云】您的验证码是: 5678", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}

// 指定模板ID单发短信
try {
    $ssender = new SmsSingleSender($appid, $appkey);
    $params = ["5678"];
    $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
        $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}

// 群发
try {
    $msender = new SmsMultiSender($appid, $appkey);
    $result = $msender->send(0, "86", $phoneNumbers,
        "【腾讯云】您的验证码是: 5678", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}

// 指定模板ID群发
try {
    $msender = new SmsMultiSender($appid, $appkey);
    $params = ["5678"];
    $result = $msender->sendWithParam("86", $phoneNumbers,
        $templateId, $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}

// 发送语音验证码
try {
    $vvcsender = new SmsVoiceVerifyCodeSender($appid, $appkey);
    $result = $vvcsender->send("86", $phoneNumber[0], "5678", 2, "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}

// 发送语音通知
try {
    $vpsender = new SmsVoicePromptSender($appid, $appkey);
    $result = $vpsender->send("86", $phoneNumber[0], 2, "5678", "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}

// 拉取短信回执以及回复
try {
    $sspuller = new SmsStatusPuller($appid, $appkey);

    // 拉取短信回执
    $callbackResult = $spuller->pullCallback(10);
    $callbackRsp = json_decode($callbackResult);
    echo $callbackResult;

    // 拉取回复
    $replyResult = $spuller->pullReply(10);
    $replyRsp = json_decode($replyResult);
    echo $replyResult;
} catch (\Exception $e) {
    echo var_dump($e);
}


// 拉取单个手机短信状态
try {
    $beginTime = 1516670595;  // 开始时间(unix timestamp)
    $endTime = 1516680595;    // 结束时间(unix timestamp)
    $maxNum = 10;             // 单次拉取最大量
    $mspuller = new SmsMobileStatusPuller($appid, $appkey);

    // 拉取短信回执
    $callbackResult = $mspuller->pullCallback("86", $phoneNumbers[0],
        $beginTime, $endTime, $maxNum);
    $callbackRsp = json_decode($callbackResult);
    echo $callbackResult;
    echo "\n";

    // 拉取回复
    $replyResult = $mspuller->pullReply("86", $phoneNumbers[0],
        $beginTime, $endTime, $maxNum);
    $replyRsp = json_decode($replyResult);
    echo $replyResult;
} catch (\Exception $e) {
    echo var_dump($e);
}
