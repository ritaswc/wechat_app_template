<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 9:50
 */

namespace app\modules\mch\controllers;

use app\models\Topic;
use app\modules\mch\models\TopicEditForm;
use yii\data\Pagination;

use app\models\TopicType;
use app\modules\mch\models\TopicTypeEditForm;

class TopicTypeController extends Controller
{
    public function actionIndex()
    {
        $query = TopicType::find()->where(['is_delete' => 0,'store_id' => $this->store->id]);

        $count = $query->count();
        $pagination = new Pagination([
            'totalCount' => $count,
        ]);
        $list = $query->limit($pagination->limit)->orderBy('sort ASC')->offset($pagination->offset)->all();
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = TopicType::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$model) {
            $model = new TopicType();
        }
        if (\Yii::$app->request->isPost) {
            $form = new TopicTypeEditForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            return $form->save();
        } else {
            foreach ($model as $index => $value) {
                $model[$index] = str_replace("\"", "&quot;", $value);
            }
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = TopicType::findOne([
            'id' => $id,
            'is_delete' => 0,
        ]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '删除成功！',
        ];
    }
}
