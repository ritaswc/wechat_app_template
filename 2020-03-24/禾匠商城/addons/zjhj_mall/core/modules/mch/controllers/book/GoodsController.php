<?php

/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/12
 * Time: 11:06
 */

namespace app\modules\mch\controllers\book;

use app\models\GoodsShare;
use app\models\Model;
use app\models\Option;
use app\models\PostageRules;
use app\models\Shop;
use app\models\YyCat;
use app\models\YyForm;
use app\models\YyGoods;
use app\modules\mch\models\book\YyCatForm;
use app\modules\mch\models\book\YyGoodsForm;
use app\modules\mch\models\LevelListForm;

class GoodsController extends Controller
{
    /**
     * @return string
     * 预约分类列表
     */
    public function actionCat()
    {
        $form = new YyCatForm();
        $arr = $form->getList($this->store->id);
        return $this->render('cat', [
            'list' => $arr[0],
            'pagination' => $arr[1],
        ]);
    }

    /**
     * @param int $id
     * @return mixed|string
     * 预约分类编辑
     */
    public function actionCatEdit($id = 0)
    {
        $cat = YyCat::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cat) {
            $cat = new YyCat();
        }
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $model['store_id'] = $this->store->id;
            $form = new YyCatForm();
            $form->attributes = $model;
            $form->cat = $cat;
            return $form->save();
        }
        foreach ($cat as $index => $value) {
            $cat[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('cat-edit', [
            'list' => $cat,
        ]);
    }

    /**
     * @param int $id
     * @return mixed|string
     * 预约分类删除
     */
    public function actionCatDel($id = 0)
    {
        $cat = YyCat::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cat) {
            return [
                'code' => 1,
                'msg' => '分类不存在或已删除'
            ];
        }

        $cat->is_delete = 1;
        if ($cat->save()) {
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

    /**
     * @return string
     * 商品列表
     */
    public function actionIndex()
    {
        $form = new YyGoodsForm();
        $arr = $form->getList($this->store->id);
        $cat_list = YyCat::find()->select('id,name')->andWhere(['store_id' => $this->store->id, 'is_delete' => 0])->orderBy('sort ASC')->asArray()->all();
        return $this->render('index', [
            'list' => $arr[0],
            'pagination' => $arr[1],
            'cat_list' => $cat_list,
        ]);
    }

    /**
     * @param int $id
     * @return mixed|string
     * 编辑预约商品
     */
    public function actionGoodsEdit($id = 0)
    {
        $form_list = YyForm::find()->where(['store_id' => $this->store->id, 'goods_id' => $id, 'is_delete' => 0])->orderBy(['sort' => SORT_ASC])->asArray()->all();

        $goods = YyGoods::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$goods) {
            $goods = new YyGoods();
        }

        $goods_share = GoodsShare::findOne(['type' => GoodsShare::SHARE_GOODS_TYPE_YY, 'store_id' => $this->store->id, 'goods_id' => $goods->id]);
        if (!$goods_share) {
            $goods_share = new GoodsShare();
        }

        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();

        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $shopId = \Yii::$app->request->post('shop_id');

            // 数组转字符串
            $newShopId = '';
            $model['shop_id'] = '';
            if (!empty($shopId)) {
                foreach ($shopId as $item) {
                    $newShopId .= $item . ',';
                }
                $model['shop_id'] = substr($newShopId, 0, strlen($newShopId) - 1);
            }
            $model['store_id'] = $this->store->id;

            $form = new YyGoodsForm();
            $form->attributes = $model;
            $form->goods = $goods;
            $form->attr = \Yii::$app->request->post('attr');

            // 单规格会员价数据
            $attr_member_price_List = [];
            foreach ($levelList as $level) {
                $keyName = 'member' . $level['level'];
                $attr_member_price_List[$keyName] = \Yii::$app->request->post($keyName);
            }
            $form->attr_member_price_List = $attr_member_price_List;

            $form->goods_share = $goods_share;
            return $form->save();
        }
        $ptCat = YyCat::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store->id])
            ->asArray()
            ->orderBy('sort ASC')
            ->all();

        // 门店列表
        $shopList = Shop::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => Model::IS_DELETE_FALSE
        ])->select('id,name')->asArray()->all();

        // 已选的门店加选中状态
        $arrShopId = explode(',', $goods['shop_id']);
        foreach ($shopList as &$item) {
            if (in_array($item['id'], $arrShopId)) {
                $item['checked'] = true;
            }
        }

        // 默认商品服务
        if (!$goods['service']) {
            $option = Option::get('good_services', $this->store->id, 'admin', []);
            foreach ($option as $service) {
                if ($service['is_default'] == 1) {
                    $goods->service = $service['service'];
                    break;
                }
            }
        }

        foreach ($goods as $index => $value) {
            if (in_array($index, ['attr', 'detail'])) {
                continue;
            }
            if (is_array($value) || is_object($value)) {
                continue;
            }
            $goods[$index] = str_replace("\"", "&quot;", $value);
        }

        $goods_share->is_level = $goods['is_level'];

        // 会员折扣默认开启
        if ($goods_share->is_level == '') {
            $goods_share->is_level = 1;
        }
        return $this->render('goods-edit', [
            'goods' => $goods,
            'cat' => $ptCat,
            'levelList' => $levelList,
            'form_list' => \Yii::$app->serializer->encode($form_list),
            'goods_share' => $goods_share,
            'cat_list' => [],
            'shop_list' => \Yii::$app->serializer->encode($shopList),
        ]);
    }

    /**
     * @param int $id
     * @param string $type
     * 上架、下架
     */
    public function actionGoodsUpDown($id = 0, $type = 'down')
    {
        if ($type == 'down') {
            $goods = YyGoods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store->id]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已下架'
                ];
            }
            $goods->status = 2;
        } elseif ($type == 'up') {
            $goods = YyGoods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 2, 'store_id' => $this->store->id]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已上架'
                ];
            }
            $goods->status = 1;
        } else {
            return [
                'code' => 1,
                'msg' => '参数错误',
            ];
        }
        if ($goods->save()) {
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            foreach ($goods->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    /**
     * 拼团商品批量操作
     */
    public function actionBatch()
    {
        $get = \Yii::$app->request->get();
        $res = 0;
        $goods_group = $get['goods_group'];
        $goods_id_group = [];
        foreach ($goods_group as $index => $value) {
            array_push($goods_id_group, $value);
        }

        $condition = ['and', ['in', 'id', $goods_id_group], ['store_id' => $this->store->id]];
        if ($get['type'] == 0) { //批量上架
            $res = YyGoods::updateAll(['status' => 1], $condition);
        } elseif ($get['type'] == 1) {//批量下架
            $res = YyGoods::updateAll(['status' => 2], $condition);
        } elseif ($get['type'] == 2) {//批量删除
            $res = YyGoods::updateAll(['is_delete' => 1], $condition);
        }
        if ($res > 0) {
            return [
                'code' => 0,
                'msg' => 'success'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => 'fail'
            ];
        }
    }

    /**
     * @param int $id
     * 拼团商品删除（逻辑删除）
     */
    public function actionGoodsDel($id = 0)
    {
        $goods = YyGoods::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品删除失败或已删除'
            ];
        }
        $goods->is_delete = 1;
        if ($goods->save()) {
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            foreach ($goods->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }
}
