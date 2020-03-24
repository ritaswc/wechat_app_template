<?php

namespace Hejiang\Sms\Senders;

use Hejiang\Sms\Exceptions\SmsException;
use Qcloud\Sms\SmsSingleSender;

/**
 * Qcloud sender
 *
 * @property-read SmsSingleSender $sdkInstance
 */
class QcloudSender extends BaseSender implements SenderInterface
{
    private $_sdkInstance;

    public function getSdkInstance()
    {
        if ($this->_sdkInstance === null) {
            $this->_sdkInstance = new SmsSingleSender($this->accessKey, $this->secretKey);
        }
        return $this->_sdkInstance;
    }

    public function send($phoneNumber, $tplId, $tplParams, $sign = '')
    {
        $responseRaw = $this->sdkInstance->sendWithParam(
            '86',
            $phoneNumber,
            $tplId,
            $tplParams,
            $sign
        );
        $response = json_decode($responseRaw);
        if ($response->result != 0) {
            throw new SmsException($response->errmsg, $response->result);
        }
        return $response->sid;
    }
}
