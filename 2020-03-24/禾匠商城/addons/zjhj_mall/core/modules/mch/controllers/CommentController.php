<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/17
 * Time: 16:53
 */

namespace app\modules\mch\controllers;

use app\models\Goods;
use app\models\OrderComment;
use app\models\User;
use app\modules\mch\models\OrderCommentForm;
use yii\data\Pagination;
use yii\helpers\Html;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $query = OrderComment::find()->alias('oc')->where(['oc.store_id' => $this->store->id, 'oc.is_delete' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query
            ->leftJoin(['u' => User::tableName()], 'oc.user_id=u.id')
            ->leftJoin(['g' => Goods::tableName()], 'oc.goods_id=g.id')
            ->select('oc.user_id as uid,oc.is_virtual,oc.virtual_user,oc.id,u.nickname,u.platform,u.avatar_url,oc.score,oc.content,oc.pic_list,g.name goods_name,oc.is_hide,oc.reply_content')
            ->orderBy('oc.addtime DESC')->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();

        foreach ($list as $k => $v) {
            if ($v['is_virtual'] == 1) {
                $list[$k]['nickname'] = '(' . $v['virtual_user'] . ')';
            }
        }
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
        ]);
    }
    public function actionReply($id, $status)
    {
        if (\Yii::$app->request->isPost) {
            $query = OrderComment::find()->where(['id' => $id])->one();
            $reply = \Yii::$app->request->post();

            if ($query == '' || !array_key_exists('reply_content', $reply)) {
                return [
                    'code' => 1,
                    'msg' => '参数错误',
                ];
            }

            $query->reply_content = $reply['reply_content'];
            if ($query->save()) {
                return [
                    'code' => 0,
                    'msg' => '回复成功',
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '回复失败',
                ];
            }
        }

        $query = OrderComment::find()->alias('oc')->where(['oc.store_id' => $this->store->id, 'oc.is_delete' => 0, 'oc.id' => $id]);
        $list = $query->leftJoin(['u' => User::tableName()], 'oc.user_id=u.id')
            ->leftJoin(['g' => Goods::tableName()], 'oc.goods_id=g.id')
            ->select('oc.id,u.nickname,oc.is_virtual,oc.virtual_user,u.avatar_url,oc.score,oc.content,oc.pic_list,g.name goods_name,oc.is_hide,oc.reply_content')
            ->asArray()->one();

        if (!$list) {
            $this->redirect(\Yii::$app->urlManager->createUrl(['mch/comment/index']))->send();
        }
        if ($list['is_virtual'] == 1) {
            $list['nickname'] = $list['virtual_user'];
        }

        return $this->render('reply', [
            'list' => $list,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = OrderComment::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_virtual' => 1,
        ]);

        if (!$model) {
            $model = new OrderComment();
        }

        if (\Yii::$app->request->isPost) {
            $form = new OrderCommentForm();

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
            $list = OrderComment::find()->select(['u.*', 'g.name'])->alias('u')
                ->where(['u.id' => $id, 'u.store_id' => $this->store->id, 'g.store_id' => $this->store->id, 'u.is_virtual' => 1])
                ->leftJoin(['g' => Goods::tableName()], 'u.goods_id=g.id')
                ->asArray()->one();

            if ($list['addtime']) {
                $list['addtime'] = date("Y/m/d H:i:s", $list['addtime']);
            }
            return $this->render('edit', [
                'model' => $list,
            ]);
        }
    }

    public function actionSearchGoods($keyword)
    {
        $keyword = trim($keyword);
        $query = Goods::find()->alias('u')->where([
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
        $order_comment = OrderComment::findOne([
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
        $order_comment = OrderComment::findOne([
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
