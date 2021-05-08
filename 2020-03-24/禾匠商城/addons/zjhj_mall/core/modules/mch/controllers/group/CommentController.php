<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/17
 * Time: 16:53
 */

namespace app\modules\mch\controllers\group;

use app\models\PtGoods;
use app\models\PtOrderComment;
use app\models\User;
use yii\data\Pagination;
use app\modules\mch\models\group\PtOrderCommentForm;
use yii\helpers\Html;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $query = PtOrderComment::find()->alias('oc')->where(['oc.store_id' => $this->store->id, 'oc.is_delete' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query
            ->leftJoin(['u' => User::tableName()], 'oc.user_id=u.id')
            ->leftJoin(['g' => PtGoods::tableName()], 'oc.goods_id=g.id')
            ->select('oc.id,oc.is_virtual,oc.virtual_user,u.nickname,u.platform,u.avatar_url,oc.score,oc.content,oc.pic_list,g.name goods_name,oc.is_hide')
            ->orderBy('oc.addtime DESC')->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
        foreach ($list as &$v) {
            if ($v['is_virtual'] == 1) {
                $v['nickname'] = '(' . $v['virtual_user'] . ')';
            }
        }
        unset($v);
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = PtOrderComment::findOne([
            'id' => $id,
            'store_id' => $this->store->id, 
            'is_virtual' => 1,
        ]);

        if (!$model) {
            $model = new PtOrderComment();
        }

        if (\Yii::$app->request->isPost) {
            $form = new PtOrderCommentForm();

            if (count(\Yii::$app->request->post('pic_list')) > 6) {
                return [
                    'code' => 1,
                    'msg' => '图片最多为6张',
                ];
            } 

            $pic_list = array();
            foreach (\Yii::$app->request->post('pic_list') as $item) {
                $pic_list[] = Html::encode($item);
            }
            $form->pic_list = \Yii::$app->serializer->encode($pic_list);
            if (\Yii::$app->serializer->encode($pic_list) === '[""]') {
                $form->pic_list = '[]';
            }

            $form->store_id = $this->store->id; 
            $form->attributes = \Yii::$app->request->post();
            $addtime = \Yii::$app->request->post('addtime');
            $form->addtime = strtotime($addtime) ? strtotime($addtime) : time();
            $form->model = $model;

            return $form->save();
        } else {
            $list = PtOrderComment::find()
            ->where([
                'store_id' => $this->store->id,
                'id' => $id,
                'is_virtual' => 1,
            ])->with(['goods' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                ]);
            }])
            ->all();
            $list = $this->simplifyData($list)[0];
            
            if ($list['addtime']) {
                $list['addtime'] = date("Y/m/d H:i:s", $list['addtime']);
            }
            return $this->render('edit', [
                'model' => $list,
            ]);
        }
    }

    private function simplifyData($data)
    {
        foreach ($data as $key => $val) {
            $newData[$key] = $val->attributes;
            if ($val->goods) {
                $newData[$key]['name'] = $val->goods->name;
            }
        }
        return $newData;
    }

    public function actionSearchGoods($keyword)
    {
        $keyword = trim($keyword);
        $query = PtGoods::find()->alias('u')->where([
            'AND',
            ['LIKE', 'u.name', $keyword],
            ['store_id' => $this->store->id],
            ['is_delete' => 0],
        ]);
        $list = $query->orderBy('u.name')->limit(30)->asArray()->select('id,name,cat_id,price')->all();
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => (object) [
                'list' => $list,
            ],
        ]; 
    }

    public function actionHideStatus($id, $status)
    {
        $order_comment = PtOrderComment::findOne([
            'store_id' => $this->store->id,
            'id' => $id,
        ]);
        if ($order_comment) {
            $order_comment->is_hide = $status;
            $order_comment->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    public function actionDeleteStatus($id, $status)
    {
        $order_comment = PtOrderComment::findOne([
            'store_id' => $this->store->id,
            'id' => $id,
        ]);
        if ($order_comment) {
            $order_comment->is_delete = $status;
            $order_comment->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }
}
