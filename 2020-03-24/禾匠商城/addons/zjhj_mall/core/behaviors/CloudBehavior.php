<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/30 17:52
 */


namespace app\behaviors;


use yii\base\ActionFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CloudBehavior extends ActionFilter
{
    public $ignores = [];

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * @param \yii\base\Action $e
     * @return bool|void
     * @throws ForbiddenHttpException
     */
    public function beforeAction($e)
    {
        if (is_array($this->ignores) && in_array($e->id, $this->ignores)) {
            return true;
        }
        $isAdmin = \Yii::$app->session->get('__is_admin');
        if (!$isAdmin) {
            throw new ForbiddenHttpException('非管理员禁止访问。');
            return false;
        }
        return true;
    }
}
