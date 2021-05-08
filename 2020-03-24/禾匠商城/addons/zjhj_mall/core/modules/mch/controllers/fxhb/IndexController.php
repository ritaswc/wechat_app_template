<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/1/31
 * Time: 10:07
 */

namespace app\modules\mch\controllers\fxhb;

use app\models\FxhbSetting;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\fxhb\FxhbListForm;
use app\modules\mch\models\fxhb\FxhbSettingForm;

class IndexController extends Controller
{
    public function actionSetting()
    {
        $model = FxhbSetting::findOne([
            'store_id' => $this->store->id,
        ]);
        if (!$model) {
            $model = new FxhbSetting();
            $model->store_id = $this->store->id;
        }
        if (\Yii::$app->request->isPost) {
            $form = new FxhbSettingForm();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            return $form->save();
        } else {
            return $this->render('setting', [
                'model' => $model,
            ]);
        }
    }

    public function actionList()
    {
        $model = new FxhbListForm();
        $model->attributes = \Yii::$app->request->get();
        $model->store_id = $this->store->id;
        $res = $model->search();
        return $this->render('list', [
            'list' => $res['data']['list'],
            'pagination' => $res['data']['pagination'],
            'get' => \Yii::$app->request->get(),
        ]);
    }
}
