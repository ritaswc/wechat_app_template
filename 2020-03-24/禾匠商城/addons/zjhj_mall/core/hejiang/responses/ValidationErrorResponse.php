<?php

namespace app\hejiang;

/**
 * Model validation error response class
 *
 * @property string $errorText
 */
class ValidationErrorResponse extends ApiResponse
{
    public function __construct($errors)
    {
        parent::__construct(1);
        $firstItem = reset($errors);
        $this->errorText = $firstItem[0];
    }

    public function getErrorText()
    {
        return $this->msg;
    }

    protected function setErrorText($value)
    {
        $this->msg = $value;
    }
}
