<?php

/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2018/3/7
 * Time: 15:45
 */

namespace app\modules\mch\controllers\miaosha;

use app\models\MsGoods;
use app\models\Option;
use app\models\PostageRules;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\miaosha\GoodsForm;

class GoodsController extends Controller
{
    /**
     * 秒杀商品列表
     */
    public function actionIndex()
    {
        $form = new GoodsForm();
        $form->store_id = $this->store->id;
        $arr = $form->getList();
        return $this->render('index', [
            'list' => $arr[0],
            'pagination' => $arr[1],
            'goodsListArray' => $arr[2]
        ]);
    }

    /**
     * @param int $id
     * @return string
     * 秒杀商品编辑
     */
    public function actionEdit($id = 0)
    {
        $goods = MsGoods::find()->andWhere(['id' => $id, 'is_delete' => 0])->one();
        $goods->isLevel;

        if (!$goods) {
            $goods = new MsGoods();
        }

        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();

        if (\Yii::$app->request->isPost) {
            $form = new GoodsForm();
            $form->store_id = $this->store->id;
            $form->attr = \Yii::$app->request->post('attr');
            $form->attributes = \Yii::$app->request->post('model');
            $form->full_cut = \Yii::$app->request->post('full_cut');
            $form->full_cut = \Yii::$app->request->post('full_cut');
            $form->integral = \Yii::$app->request->post('integral');

            $attr_member_price_List = [];
            foreach ($levelList as $level) {
                $keyName = 'member' . $level['level'];
                $attr_member_price_List[$keyName] = \Yii::$app->request->post($keyName);
            }
            $form->attr_member_price_List = $attr_member_price_List;

            $form->goods = $goods;
            return $form->save();
        }

        $postageRiles = PostageRules::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->all();
        if ($goods->full_cut) {
            $goods->full_cut = json_decode($goods->full_cut, true);
        } else {
            $goods->full_cut = [
                'pieces' => '',
                'forehead' => '',
            ];
        }
        if ($goods->integral) {
            $goods->integral = json_decode($goods->integral, true);
        } else {
            $goods->integral = [
                'give' => 0,
                'deduction' => 0,
                'more' => 0,
            ];
        }
        foreach ($goods as $index => $value) {
            if (in_array($index, ['attr', 'full_cut', 'integral', 'payment', 'detail'])) {
                continue;
            }
            if (is_array($value) || is_object($value)) {
                continue;
            }
            $goods[$index] = str_replace("\"", "&quot;", $value);
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

        return $this->render('edit', [
            'levelList' => $levelList,
            'goods' => $goods,
            'postageRiles' => $postageRiles,
        ]);
    }

    /**
     * 删除（逻辑）
     * @param int $id
     */
    public function actionDel($id = 0)
    {
        $goods = MsGoods::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品删除失败或已删除',
            ];
        }
        $goods->is_delete = 1;
        if ($goods->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
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

    //商品上下架
    public function actionGoodsUpDown($id = 0, $type = 'down')
    {
        if ($type == 'down') {
            $goods = MsGoods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store->id]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已下架',
                ];
            }
            $goods->status = 0;
        } elseif ($type == 'up') {
            $goods = MsGoods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 0, 'store_id' => $this->store->id]);

            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已上架',
                ];
            }
            if (!$goods->getNum()) {
                $return_url = \Yii::$app->urlManager->createUrl(['mch/miaosha/goods/edit', 'id' => $goods->id]);
                if (!$goods->use_attr) {
                    $return_url = \Yii::$app->urlManager->createUrl(['mch/miaosha/goods/edit', 'id' => $goods->id]) . '#step3';
                }

                return [
                    'code' => 1,
                    'msg' => '商品库存不足，请先完善商品库存',
                    'return_url' => $return_url,
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
                'msg' => '成功',
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
     * 批量设置
     */
    public function actionBatch()
    {
        $get = \Yii::$app->request->get();
        $res = 0;
        $goods_group = $get['goods_group'];
        $goods_id_group = [];
        foreach ($goods_group as $index => $value) {
            if ($get['type'] == 0) {
                if ($value['num'] != 0) {
                    array_push($goods_id_group, $value['id']);
                }
            } else {
                array_push($goods_id_group, $value['id']);
            }
        }

        $condition = ['and', ['in', 'id', $goods_id_group], ['store_id' => $this->store->id]];
        if ($get['type'] == 0) { //批量上架
            $res = MsGoods::updateAll(['status' => 1], $condition);
        } elseif ($get['type'] == 1) { //批量下架
            $res = MsGoods::updateAll(['status' => 0], $condition);
        } elseif ($get['type'] == 2) { //批量删除
            $res = MsGoods::updateAll(['is_delete' => 1], $condition);
        }
        if ($res > 0) {
            return [
                'code' => 0,
                'msg' => 'success',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => 'fail',
            ];
        }
    }

    /**
     * 批量设置积分
     */
    public function actionBatchIntegral()
    {
        $get = \Yii::$app->request->get();
        $integral['give'] = $get['give'] ?: 0;
        $integral['forehead'] = $get['forehead'] ?: 0;
        $integral['more'] = $get['more'] ?: 0;

        $integral = json_encode($integral, JSON_UNESCAPED_UNICODE);

        if (empty($get['goods_group'])) {
            return [
                'code' => 1,
                'msg' => '请选择商品',
            ];
        }
        $res = MsGoods::updateAll(['integral' => $integral], ['in', 'id', $get['goods_group']]);
        if ($res) {
            return [
                'code' => 0,
                'msg' => 'success',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '系统错误',
            ];
        }
    }
}
