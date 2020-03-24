<?php

namespace Hejiang\Sms\Messages;

/**
 * Template SMS
 */
class TemplateMessage extends BaseMessage
{
    public $tplId;

    public $tplParams;

    public function send()
    {
        return $this->sender->send(
            $this->phoneNumber,
            $this->tplId,
            $this->tplParams,
            $this->sign
        );
    }
}
