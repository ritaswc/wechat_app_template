<?php

namespace app\modules\mch\controllers;

use app\models\User;
use app\modules\mch\models\goods\Taobaocsv;
use app\modules\mch\models\ShareErrorForm;
use app\utils\Sms;

/**
 * Default controller for the `mch` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(\Yii::$app->urlManager->createUrl(['mch/store/index']))->send();
    }

    // 淘宝csv文件上传
    public function actionTaobaoCsv()
    {
        $form = new Taobaocsv();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;dd($form->search());
        return $form->search();
    }

    public function actionShareError()
    {
        if (\Yii::$app->request->isAjax) {
            $token = \Yii::$app->request->get('token');
            if (!$token) {
                $token = \Yii::$app->request->post('token');
            }
            if (md5($token) != 'd5e5c17015213c6e90778f8d7e9c670a') {
                return [
                    'code' => 1,
                    'msg' => '错误！禁止访问！'
                ];
            }
            $form = new ShareErrorForm();
            $form->attributes = \Yii::$app->request->get();
            return $form->save();
        }
        $form = new ShareErrorForm();
        $res = $form->search();
        return $this->render('share-error', [
            'list' => $res['list'],
            'pagination' => $res['pagination']
        ]);
    }
}
