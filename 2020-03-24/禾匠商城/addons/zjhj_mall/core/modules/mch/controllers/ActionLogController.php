<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 14:27
 */

namespace app\modules\mch\controllers;

use app\models\ActionLog;
use app\models\Option;
use app\modules\mch\models\ActionLogForm;

class ActionLogController extends Controller
{
    public function actionIndex()
    {
        $model = new ActionLogForm();
        $res = $model->getActionLogList();

        return $this->render('index', ['list' => $res['list'], 'pagination' => $res['pagination']]);
    }

    public function actionSwitch()
    {
        if (\Yii::$app->request->isPost) {
            $value = \Yii::$app->request->post('switch');
            Option::set(ActionLog::OPTION_NAME, $value, $this->store->id, 'admin');

            return [
                'code' => 0,
                'msg' => '设置成功'
            ];
        }

        $option = Option::get(ActionLog::OPTION_NAME, $this->store->id, 'admin');

        return $this->render('action-log-switch', ['option' => $option]);
    }
}
