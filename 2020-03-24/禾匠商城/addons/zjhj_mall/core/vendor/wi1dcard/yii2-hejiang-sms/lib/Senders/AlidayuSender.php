<?php

namespace Hejiang\Sms\Senders;

use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Hejiang\Sms\Exceptions\SmsException;

/**
 * Alidayu sender
 */
class AlidayuSender extends BaseSender implements SenderInterface
{
    public function send($phoneNumber, $tplId, $tplParams, $sign)
    {
        $client = new Client(new App([
            'app_key' => $this->accessKey,
            'app_secret' => $this->secretKey,
        ]));

        $request = new AlibabaAliqinFcSmsNumSend;

        $request->setRecNum($phoneNumber)
            ->setSmsTemplateCode($tplId)
            ->setSmsParam($tplParams)
            ->setSmsFreeSignName($sign);

        $response = $client->execute($request);

        if (isset($response->code)) {
            throw new SmsException($response->msg, $response->code);
        }

        return '';
    }
}
