<?php

/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */

namespace app\modules\user\controllers\mch;

use app\models\Goods;
use app\models\Mch;
use app\models\MchCat;
use app\models\MchGoodsCat;
use app\models\OrderMessage;
use app\models\tplmsg\AdminTplMsgSender;
use app\modules\mch\models\goods\Taobaocsv;
use app\modules\user\behaviors\MchBehavior;
use app\modules\user\behaviors\UserLoginBehavior;
use app\modules\user\controllers\Controller;
use app\modules\user\models\mch\CatEditForm;
use app\modules\user\models\mch\CatListForm;
use app\modules\user\models\mch\GoodsEditForm;
use yii\data\Pagination;

class GoodsController extends Controller
{

    public function behaviors()
    {
        return [
            'login' => [
                'class' => UserLoginBehavior::className(),
            ],
            'mch' => [
                'class' => MchBehavior::className(),
            ],
        ];
    }

    public function actionIndex($keyword = null, $cat_id = null)
    {
        $keyword = trim($keyword);
        $query = Goods::find()->where(['mch_id' => $this->mch->id, 'is_delete' => 0]);
        if ($keyword) {
            $query->andWhere(['LIKE', 'name', $keyword]);
        }
        if ($cat_id) {
            $sub_query = MchGoodsCat::find()->select('goods_id')->where(['cat_id' => $cat_id]);
            $query->andWhere(['id' => $sub_query]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->select('id,name,cover_pic,status,attr,mch_sort')
            ->orderBy('mch_sort,addtime DESC')->limit($pagination->limit)->offset($pagination->offset)->all();
        return $this->render('index', [
            'list' => $list,
            'pagination' => $pagination,
            'keyword' => $keyword,
            'cat_list' => MchCat::find()->where(['mch_id' => $this->mch->id, 'is_delete' => 0, 'parent_id' => 0])->orderBy('sort,addtime DESC')->all(),
            'get' => \Yii::$app->request->get(),
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = Goods::findOne(['id' => $id, 'mch_id' => $this->mch->id, 'is_delete' => 0]);
        if (!$model) {
            $model = new Goods();
            $model->mch_id = $this->mch->id;
            $model->store_id = $this->mch->store_id;
        }
        $form = new GoodsEditForm();
        $form->model = $model;
        $form->mch = $this->mch;
        if (\Yii::$app->request->isPost) {
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        } else {
            if (\Yii::$app->request->isAjax) {
                return $form->search();
            } else {
                $cat_list_form = new CatListForm();
                $cat_list_form->mch_id = $this->mch->id;
                return $this->render('edit');
            }
        }
    }

    /**
     * 商品删除
     */
    public function actionDelete($id)
    {
        $model = Goods::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
        ]);
        if (!$model) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        $model->is_delete = 1;
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => '删除成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '删除失败',
            ];
        }
    }

    /**
     * 分类列表
     */
    public function actionCat()
    {
        $cat_list = MchCat::find()->where(['mch_id' => $this->mch->id, 'is_delete' => 0, 'parent_id' => 0])->orderBy('sort,addtime DESC')->all();
        return $this->render('cat', [
            'cat_list' => $cat_list,
        ]);
    }

    /**
     * 分类编辑
     */
    public function actionCatEdit($id = null)
    {
        $cat = MchCat::findOne(['id' => $id, 'mch_id' => $this->mch->id, 'is_delete' => 0]);
        if (!$cat) {
            $cat = new MchCat();
            $cat->mch_id = $this->mch->id;
        }
        if (\Yii::$app->request->isPost) {
            $form = new CatEditForm();
            $form->attributes = \Yii::$app->request->post('model');
            $form->model = $cat;
            return $form->save();
        }
        $parent_list_query = MchCat::find()->where([
            'mch_id' => $this->mch->id,
            'is_delete' => 0,
            'parent_id' => 0,
        ]);
        if (!$cat->isNewRecord && $cat->parent_id == 0) {
            $parent_list_query->andWhere([
                'id' => -1,
            ]);
        }
        $parent_list = $parent_list_query->all();
        return $this->render('cat-edit', [
            'parent_list' => $parent_list,
            'list' => $cat,
        ]);
    }

    /**
     * 分类删除
     */
    public function actionCatDel($id)
    {
        $model = MchCat::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
        ]);
        if (!$model) {
            return [
                'code' => 1,
                'msg' => '分类不存在',
            ];
        }
        $model->is_delete = 1;
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => '删除成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '删除失败',
            ];
        }
    }

    /**
     * 改变商品上下架状态
     */
    public function actionSetStatus($id, $status)
    {
        $model = Goods::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
        ]);
        if (!$model) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        if ($status != 0) {
            return [
                'code' => 1,
                'msg' => '系统错误，请刷新重试',
            ];
        }
        $model->status = $status == 1 ? 1 : 0;
        if ($model->status == 1 && !$model->getNum()) {
            return [
                'code' => 1,
                'msg' => '商品库存为0上架失败，请先设置商品库存',
            ];
        }
        if ($model->save()) {
            return [
                'code' => 0,
                'msg' => $model->status == 1 ? '上架成功' : '下架成功',
            ];
        } else {
            return [
                'code' => 0,
                'msg' => $status == 1 ? '上架失败' : '下架失败',
            ];
        }
    }

    public function actionUpdateGoodsNum($offset)
    {
        /** @var Goods[] $list */
        $list = Goods::find()->select('id,attr,goods_num')->where(['mch_id' => $this->mch->id, 'is_delete' => 0])
            ->offset($offset)->limit(10)->all();
        foreach ($list as $item) {
            $item->updateAttributes([
                'goods_num' => $item->getNum(),
            ]);
        }
        if (!is_array($list) || !count($list)) {
            return [
                'code' => 0,
                'msg' => '更新完成',
            ];
        } else {
            return [
                'code' => 0,
                'msg' => 'success',
                'continue' => 1,
            ];
        }
    }

    public function actionApply($id)
    {
        $model = Goods::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
            'store_id' => $this->store->id,
        ]);
        if (!$model) {
            return [
                'code' => 1,
                'msg' => '商品不存在',
            ];
        }
        OrderMessage::set($model->id, $model->store_id, 4, 1);
        AdminTplMsgSender::sendMchUploadGoods($this->store->id, [
            'goods' => $model->name,
        ]);
        return [
            'code' => 0,
            'msg' => '提交成功',
        ];
    }

    // 淘宝CSV上传
    public function actionTaobaoCopy()
    {
        if(\Yii::$app->request->isPost){
            $form = new Taobaocsv();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $form->mch_id = $this->mch->id;
            $res = $form->search();
            return $res;
        }
        return $this->render('@app/modules/mch/views/goods/taobao-copy');
    }
}
