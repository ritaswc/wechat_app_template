<?php

namespace Hejiang\Storage\Drivers;

use Hejiang\Storage\Helpers\UrlConverter;

abstract class BaseDriver extends \yii\base\Component
{
    public $bucket = '';

    public $accessKey = '';

    public $secretKey = '';

    protected $urlCallback = null;

    public function init()
    {
        parent::init();
        if ($this->urlCallback === null) {
            $this->urlCallback = new UrlConverter();
        }
    }

    public function getUrlCallback()
    {
        return $this->urlCallback;
    }

    public function setUrlCallback(callable $cb)
    {
        $this->urlCallback = $cb;
    }

    public function saveFile($localFile, $saveTo)
    {
        $url = $this->put($localFile, $saveTo);
        return call_user_func_array($this->urlCallback, [$url, $saveTo, $this]);
    }

    abstract public function put($localFile, $saveTo);
}
