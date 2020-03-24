<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/4/4
 * Time: 14:53
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\controllers;


use app\models\ContactForm;

class ContactController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionCallback()
    {
        if (\Yii::$app->request->isPost) {
            $form = new ContactForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->search());
        }
    }
}
