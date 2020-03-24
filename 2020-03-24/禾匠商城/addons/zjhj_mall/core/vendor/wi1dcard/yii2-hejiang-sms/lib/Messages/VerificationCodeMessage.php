<?php

namespace Hejiang\Sms\Messages;

/**
 * Verification code SMS
 */
class VerificationCodeMessage extends TemplateMessage
{
    private $code;

    public $codePointer;

    public function send()
    {
        $this->codePointer = $this->getCode();
        return $this->sender->send(
            $this->phoneNumber,
            $this->tplId,
            $this->tplParams,
            $this->sign
        );
    }

    public function getCode()
    {
        if (!$this->code) {
            $this->code = $this->generate();
        }
        return $this->code;
    }

    protected function generate($length = 6)
    {
        return mt_rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }

    public function validate($input)
    {
        return $this->code === $input;
    }
}
