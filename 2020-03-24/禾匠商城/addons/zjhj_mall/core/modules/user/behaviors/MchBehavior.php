<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/9
 * Time: 15:36
 */

namespace app\modules\user\behaviors;

use app\models\Mch;
use app\models\User;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\web\Controller;

class MchBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * @param ActionEvent $e
     */
    public function beforeAction($e)
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        if (!$user) {
            \Yii::$app->end();
        }
        $mch = Mch::findOne([
            'user_id' => $user->id,
            'is_delete' => 0,
        ]);
        if (!$mch) {
            \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['user/default/setting',]));
            \Yii::$app->end();
        }
        if ($mch->review_status != 1) {
            \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['user/default/setting',]));
            \Yii::$app->end();
        }
        $e->action->controller->mch = $mch;
    }
}
