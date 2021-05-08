<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 11:16
 */

namespace app\modules\mch\controllers;

use app\models\Recharge;
use app\models\RechargeModule;
use app\modules\mch\models\recharge\RechargeForm;
use app\modules\mch\models\recharge\SettingForm;

class RechargeController extends Controller
{
    public function actionIndex()
    {
        $list = Recharge::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])
            ->orderBy(['pay_price' => SORT_DESC])->asArray()->all();
        return $this->render('index', [
            'list'=>$list
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = Recharge::findOne(['id' => $id, 'is_delete' => 0,'store_id'=>$this->store->id]);
        if (!$model) {
            $model = new Recharge();
        }
        if (\Yii::$app->request->isPost) {
            $form = new RechargeForm();
            $form->model = $model;
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
        foreach ($model as $index => $value) {
            $model[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('edit', [
            'model' => $model
        ]);
    }

    public function actionDel($id = null)
    {
        $model = Recharge::findOne(['id' => $id, 'is_delete' => 0,'store_id'=>$this->store->id]);
        if (!$model) {
            return [
                'code'=>1,
                'msg'=>'请刷新重试'
            ];
        }
        $model->is_delete = 1;
        if ($model->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            foreach ($model->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    public function actionSetting()
    {
        $form_1 = new RechargeModule();
        $form_1->store_id = $this->store->id;
        $list = $form_1->search_recharge();
        if (\Yii::$app->request->isPost) {
            $form = new SettingForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
        return $this->render('setting', [
            'list'=>$list
        ]);
    }
}
