<?php

namespace Hejiang\Express\Exceptions;

class TrackingException extends \Exception
{
    protected $response = '';

    public function __construct($message, $response = null)
    {
        parent::__construct($message);
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
