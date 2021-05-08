<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/9
 * Time: 10:22
 */

namespace app\modules\user\controllers;

use app\modules\user\behaviors\UserLoginBehavior;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'login' => [
                'class' => UserLoginBehavior::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        return \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['user/mch/index/index']))->send();
    }

    public function actionSetting()
    {
        $user = \Yii::$app->user->identity;
        return $this->render('setting', [
            'user' => $user,
        ]);
    }
}
