<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers;


use app\models\ActionLog;
use app\models\Model;
use yii\data\Pagination;

class LogController extends Controller
{
    public function actionIndex()
    {
        $query = ActionLog::find()->where([
            'is_delete' => Model::IS_DELETE_FALSE,
            'type' => 1,
        ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query->orderBy('addtime DESC')->limit($pagination->limit)->offset($pagination->offset)
            ->all();


        return $this->render('index', ['list' => $list, 'pagination' => $pagination]);
    }

    public function actionEdit($id)
    {
        $actionLog = ActionLog::findOne(['id' => $id, 'is_delete' => Model::IS_DELETE_FALSE]);

        return $this->render('edit', [
            'detail' => $actionLog
        ]);
    }

    public function actionDel()
    {
        $actionLog = ActionLog::updateAll(['is_delete' => 1], ['is_delete' => 0, 'type' => 1]);

        return [
            'code' => 0,
            'msg' => '清空成功' . $actionLog
        ];
    }
}