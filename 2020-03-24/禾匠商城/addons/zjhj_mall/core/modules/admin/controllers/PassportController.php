<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 13:52
 */

namespace app\modules\admin\controllers;

use app\models\Option;
use app\modules\admin\models\LoginForm;
use app\modules\admin\models\password\RegisterForm;
use app\modules\admin\models\password\ResetPasswordForm;
use app\modules\admin\models\password\SendRegisterSmsCodeForm;
use app\modules\admin\models\password\SendSmsCodeForm;
use yii\web\HttpException;

class PassportController extends Controller
{
    public $layout = 'passport';

    public function behaviors()
    {
        return [];
    }

    public function actions()
    {
        return [
            'captcha' => \app\utils\ClearCaptchaAction::className(),
            'sms-code-captcha' => \app\utils\ClearCaptchaAction::className(),
        ];
    }

    //登录
    public function actionLogin()
    {
        \Yii::$app->session->open();
        if (\Yii::$app->request->isAjax) {
            $form = new LoginForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->login();
        } else {
            return $this->render('login');
        }
    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->admin->logout();
        \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['admin']))->send();
    }

    //发送短信验证码，修改密码用
    public function actionSendSmsCode()
    {
        $form = new SendSmsCodeForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->send();
    }

    //通过短信验证重置密码
    public function actionResetPassword()
    {
        $form = new ResetPasswordForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->save();
    }

    //注册
    public function actionRegister()
    {
        $open_register = Option::get('open_register', 0, 'admin', false);
        if (!$open_register) {
            throw new HttpException(403, '注册功能暂未开放。');
        }
        if (\Yii::$app->request->isPost) {
            $form = new RegisterForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            return $this->render('register');
        }
    }


    //注册 数据验证
    public function actionRegisterValidate()
    {
        $form = new RegisterForm();
        $form->attributes = \Yii::$app->request->post();
        $form->post_attrs = \Yii::$app->request->post();
        return $form->validateAttr();
    }

    //发送短信验证码，注册用
    public function actionSendRegisterSmsCode()
    {
        $form = new SendRegisterSmsCodeForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->send();
    }
}
