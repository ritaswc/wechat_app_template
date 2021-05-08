<?php

/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2018/2/24
 * Time: 10:35
 */

namespace app\modules\mch\controllers\group;

use app\models\PtRobot;
use app\modules\mch\models\group\RobotForm;

/**
 * Class RobotController
 * @package app\modules\mch\controllers\group
 * 拼团机器人
 */
class RobotController extends Controller
{
    /**
     * @return string
     * 拼团机器人列表
     */
    public function actionIndex()
    {
        $form = new RobotForm();
        $form->store_id = $this->store->id;
        $arr = $form->getList();
        return $this->render('index', [
            'list' => $arr[0],
            'pagination' => $arr[1],
        ]);
    }

    /**
     * @param int $id
     * @return string
     * 拼团机器人编辑
     */
    public function actionEdit($id = 0)
    {
        $robot = PtRobot::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$robot) {
            $robot = new PtRobot();
        }
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $model['store_id'] = $this->store->id;
            $form = new RobotForm();
            $form->attributes = $model;
            $form->robot = $robot;
            return $form->save();
        }
        foreach ($robot as $index => $value) {
            $robot[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('edit', [
            'list' => $robot,
        ]);
    }

    /**
     * @param int $id
     * 机器人删除
     */
    public function actionDel($id = 0)
    {
        $robot = PtRobot::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$robot) {
            return [
                'code' => 1,
                'msg' => '机器人不存在或已删除'
            ];
        }

        $robot->is_delete = 1;

        if ($robot->save()) {
            return [
                'code' => 0,
                'msg' => '删除成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '删除失败'
            ];
        }
    }
}
