<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 9:50
 */

namespace app\modules\mch\controllers;

use app\models\Goods;
use app\models\Topic;
use app\models\TopicType;
use app\models\Video;
use app\modules\mch\models\TopicEditForm;
use yii\data\Pagination;

class TopicController extends Controller
{
    public function actionIndex($type = 0)
    {
        $subQuery = (new \yii\db\Query())->select(['id as typeid', 'name'])->from(TopicType::tableName())->where(['store_id' => $this->store->id, 'is_delete' => 0]);
        $query = (new \yii\db\Query())->select([])->from(['topic' => Topic::tableName()])->leftjoin(['tc' => $subQuery], 'typeid=topic.type')->where(['store_id' => $this->store->id, 'is_delete' => 0]);
        if ($type != 0) {
            $query->andWhere('type=:type', [':type' => $type]);
        }

        $count = $query->count();
        $pagination = new Pagination([
            'totalCount' => $count,
        ]);
        $list = $query->orderBy('sort ASC,addtime DESC')->limit($pagination->limit)->offset($pagination->offset)->all();

        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
            'select' => $subQuery->all(),
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = Topic::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);

        if (!$model) {
            $model = new Topic();
        }
        if (\Yii::$app->request->isPost) {
            $form = new TopicEditForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            return $form->save();
        } else {
            $TopicType = TopicType::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->all();
            $select = array();
            foreach ($TopicType as $k => $v) {
                $select[$k] = (object) [
                    'value' => $v['id'],
                    'name' => $v['name'],
                ];
            }
            foreach ($model as $index => $value) {
                $model[$index] = str_replace("\"", "&quot;", $value);
            }

            return $this->render('edit', [
                'model' => $model,
                'select' => $select,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = Topic::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if ($model) {
            $model->is_delete = 1;
            $model->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功！',
        ];
    }

    public function actionSearchGoods($keyword = null)
    {
        $query = Goods::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if ($keyword) {
            $query->andWhere(['LIKE', 'name', $keyword]);
        }

        $list = $query->orderBy('sort ASC,addtime DESC')->limit(10)->all();
        $new_list = [];
        foreach ($list as $item) {
            $new_list[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'cover_pic' => $item->getGoodsCover(),
            ];
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $new_list,
            ],
        ];
    }

    public function actionSearchVideo($keyword = null)
    {
        $query = Video::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if ($keyword) {
            $query->andWhere(['LIKE', 'title', $keyword]);
        }

        $list = $query->orderBy('sort ASC,addtime DESC')->limit(10)->all();
        $new_list = [];
        foreach ($list as $item) {
            $new_list[] = [
                'id' => $item->id,
                'name' => $item->title,
                'src' => $item->url,
                'cover_pic' => $item->pic_url,
            ];
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $new_list,
            ],
        ];
    }
}
