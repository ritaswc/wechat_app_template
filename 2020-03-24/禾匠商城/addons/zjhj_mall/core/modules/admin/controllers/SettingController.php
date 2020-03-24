<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/26
 * Time: 16:06
 */

namespace app\modules\admin\controllers;

use app\models\Option;
use app\modules\admin\behaviors\AdminBehavior;
use app\modules\admin\behaviors\LoginBehavior;

class SettingController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
            'admin' => [
                'class' => AdminBehavior::className(),
            ],
        ]);
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isPost) {
            Option::setList([
                [
                    'name' => 'name',
                    'value' => \Yii::$app->request->post('name'),
                ],
                [
                    'name' => 'logo',
                    'value' => \Yii::$app->request->post('logo'),
                ],
                [
                    'name' => 'copyright',
                    'value' => \Yii::$app->request->post('copyright'),
                ],
                [
                    'name' => 'max_login_error',
                    'value' => intval(\Yii::$app->request->post('max_login_error')),
                ],
                [
                    'name' => 'passport_bg',
                    'value' => \Yii::$app->request->post('passport_bg'),
                ],
                [
                    'name' => 'open_register',
                    'value' => \Yii::$app->request->post('open_register'),
                ],
                [
                    'name' => 'ind_sms',
                    'value' => \Yii::$app->request->post('ind_sms'),
                ],
            ], 0, 'admin');
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->render('index', [
                'option' => Option::getList('name,logo,copyright,max_login_error,passport_bg,open_register,ind_sms', 0, 'admin'),
            ]);
        }
    }
}
