<?php

namespace app\behaviors;

use yii\base\Behavior;
use yii\web\Controller;

class BaseBehavior extends Behavior
{
    public function beforeActionBase($event)
    {
        if (static::matchRoutes($this->only_routes)) {
            return $this->beforeAction($event);
        }
    }

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeActionBase'
        ];
    }

    public static function matchRoutes($routes)
    {
        $route = \Yii::$app->requestedRoute;
        if (is_array($routes)) {
            foreach ($routes as $r) {
                if ($r == $route) {
                    return true;
                }
                $r = str_replace('/', '\/', $r);
                $r = str_replace('*', '.*', $r);
                $r = "/^{$r}$/";
                if (preg_match($r, $route, $res)) {
                    return true;
                }
            }
        }
        return false;
    }
}
