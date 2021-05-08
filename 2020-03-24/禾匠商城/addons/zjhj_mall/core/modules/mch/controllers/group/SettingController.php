<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 17:42
 */

namespace app\modules\mch\controllers\group;

use app\models\Option;
use app\models\PtSetting;
use app\modules\mch\models\group\SettingModel;

class SettingController extends Controller
{
    public function actionIndex()
    {
        $setting = PtSetting::findOne(['store_id'=>$this->store->id]);
        if (!$setting) {
            $setting = new PtSetting();
        }
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $form = new SettingModel();
            $form->store_id = $this->store->id;
            $form->setting = $setting;
            $form->attributes = $model;
            return $form->save();
        } else {
            return $this->render('index', [
                'setting'=>$setting
            ]);
        }
    }
}
