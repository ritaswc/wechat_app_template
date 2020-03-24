<?php

namespace Hejiang\Sms\Senders;

abstract class BaseSender extends \yii\base\Component
{
    public $accessKey;

    public $secretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }
}
