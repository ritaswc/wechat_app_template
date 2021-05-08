<?php

namespace app\utils;

use yii\base\InlineAction;

class RedirectAction
{
    protected $actionId;

    public function __construct($actionId)
    {
        $this->actionId = $actionId;
    }

    public function __invoke($id, \yii\base\Controller $controller)
    {
        return $controller->createAction($this->actionId);
    }
}
