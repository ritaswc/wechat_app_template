<?php

namespace Hejiang\Sms\Senders;

interface SenderInterface
{
    /**
     * Send SMS
     *
     * @param string $phoneNumber
     * @param string $tplId
     * @param array $tplParams
     * @param string $sign
     * @return string Unique request ID, required for querying sending status.
     */
    public function send($phoneNumber, $tplId, $tplParams, $sign);
}
