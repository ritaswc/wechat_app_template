<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 10:56
 */

namespace app\modules\mch\controllers;

use app\models\Attr;
use app\models\AttrGroup;
use app\models\Cat;
use app\models\Goods;
use app\models\GoodsPic;
use app\models\MsGoods;
use app\models\Option;
use app\models\PtGoods;
use app\models\YyGoods;
use app\modules\mch\events\goods\BaseAddGoodsEvent;
use app\modules\mch\models\CopyForm;
use app\modules\mch\models\goods\Taobaocsv;
use app\modules\mch\models\GoodsForm;
use app\modules\mch\models\GoodsQrcodeForm;
use app\modules\mch\models\GoodsSearchForm;
use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\SetGoodsSortForm;
use Hejiang\Event\EventArgument;
use yii\web\HttpException;

/**
 * Class GoodController
 * @package app\modules\mch\controllers
 * 商品
 */
class GoodsController extends Controller
{

    /**
     * 商品分类删除
     * @param int $id
     */
    public function actionGoodClassDel($id = 0)
    {
        $dishes = Cat::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$dishes) {
            return [
                'code' => 1,
                'msg' => '商品分类删除失败或已删除',
            ];
        }
        $dishes->is_delete = 1;
        if ($dishes->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($dishes->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    public function actionGetCatList($parent_id = 0)
    {
        $list = Cat::find()->select('id,name')->where(['is_delete' => 0, 'parent_id' => $parent_id, 'store_id' => $this->store->id])->asArray()->all();
        return [
            'code' => 0,
            'data' => $list,
        ];
    }

    /**
     * 商品管理
     * @return string
     */
    public function actionGoods($keyword = null, $status = null)
    {
        $form = new GoodsSearchForm();
        $form->store = $this->store;
        $form->keyword = $keyword;
        $form->status = $status;
        $form->plugin = get_plugin_type();
        $res = $form->getList();

        return $this->render('goods', [
            'list' => $res['list'],
            'goodsList' => $res['goodsList'],
            'pagination' => $res['pagination'],
            'cat_list' => $res['cat_list'],
        ]);
    }

    // 后台商品小程序码
    public function actionGoodsQrcode()
    {
        $form = new GoodsQrcodeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->plugin = get_plugin_type();
        if (!\Yii::$app->user->isGuest) {
            $form->user_id = \Yii::$app->user->id;
        }
        return $form->search();
    }

    /**
     * 商品修改
     * @param int $id
     * @return string
     */
    public function actionGoodsEdit($id = 0)
    {
        $goods = Goods::findOne(['id' => $id, 'store_id' => $this->store->id, 'mch_id' => 0]);
        if (!$goods) {
            $goods = new Goods();
        }
        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();

        $form = new GoodsForm();
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            if ($model['quick_purchase'] == 0) {
                $model['hot_cakes'] = 0;
            }
            $model['store_id'] = $this->store->id;
            $form->attributes = $model;
            $form->attr = \Yii::$app->request->post('attr');
            $form->goods_card = \Yii::$app->request->post('goods_card');
            $form->full_cut = \Yii::$app->request->post('full_cut');
            $form->integral = \Yii::$app->request->post('integral');

            // 单规格会员价数据
            $attr_member_price_List = [];
            foreach ($levelList as $level) {
                $keyName = 'member' . $level['level'];
                $attr_member_price_List[$keyName] = \Yii::$app->request->post($keyName);
            }
            $form->attr_member_price_List = $attr_member_price_List;

            $form->goods = $goods;
            $form->plugins = \Yii::$app->request->post('plugins');
            return $form->save();
        }

        $searchForm = new GoodsSearchForm();
        $searchForm->goods = $goods;
        $searchForm->store = $this->store;
        $list = $searchForm->search();

        $args = new EventArgument();
        $args['goods'] = $goods;
        \Yii::$app->eventDispatcher->dispatch(new BaseAddGoodsEvent(), $args);
        $plugins = $args->getResults();

        // 默认商品服务
        if (!$goods['service']) {
            $option = Option::get('good_services', $this->store->id, 'admin', []);
            foreach ($option as $item) {
                if ($item['is_default'] == 1) {
                    $list['goods']['service'] = $item['service'];
                    break;
                }
            }
        }

        // 会员折扣默认开启
        if ($list['goods']->is_level == '') {
            $list['goods']->is_level = 1;
        }
        return $this->render('goods-edit', [
            'goods' => $list['goods'],
            'cat_list' => $list['cat_list'],
            'levelList' => $levelList,
            'postageRiles' => $list['postageRiles'],
            'card_list' => \Yii::$app->serializer->encode($list['card_list']),
            'goods_card_list' => \Yii::$app->serializer->encode($list['goods_card_list']),
            'goods_cat_list' => \Yii::$app->serializer->encode($list['goods_cat_list']),
            'plugins' => $plugins
        ]);
    }

    /**
     * 删除（逻辑）
     * @param int $id
     */
    public function actionGoodsDel($id = 0)
    {
        $goods = Goods::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
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
            $goods = Goods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store->id]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已下架',
                ];
            }
            $goods->status = 0;
        } elseif ($type == 'up') {
            $goods = Goods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 0, 'store_id' => $this->store->id]);

            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已上架',
                ];
            }
            if ($goods->cat_id == 0 && count(Goods::getCatList($goods)) == 0) {
                return [
                    'code' => 1,
                    'msg' => '请先选择分类'
                ];
            }
            if (!$goods->getNum() && $goods->mch_id == 0) {
                $return_url = \Yii::$app->urlManager->createUrl([get_plugin_url() . '/goods-edit', 'id' => $goods->id]);
                if (!$goods->use_attr) {
                    $return_url = \Yii::$app->urlManager->createUrl([get_plugin_url() . '/goods-edit', 'id' => $goods->id]) . '#step3';
                }

                return [
                    'code' => 1,
                    'msg' => '商品库存不足，请先完善商品库存',
                    'return_url' => $return_url,
                ];
            }
            $goods->status = 1;
        } elseif ($type == 'start') {
            $goods = Goods::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);

            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已加入',
                ];
            }
            $goods->quick_purchase = 1;
        } elseif ($type == 'close') {
            $goods = Goods::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);

            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已关闭',
                ];
            }
            $goods->quick_purchase = 0;
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
     * 商品规格库存管理
     * @param int $id 商品id
     */
    public function actionGoodsAttr($id)
    {
        $goods = Goods::findOne([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'id' => $id,
        ]);
        if (!$goods) {
            throw new HttpException(404);
        }

        if (\Yii::$app->request->isPost) {
            $goods->attr = \Yii::$app->serializer->encode(\Yii::$app->request->post('attr', []));
            if ($goods->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '保存失败',
                ];
            }
        } else {
            $attr_group_list = AttrGroup::find()
                ->select('id attr_group_id,attr_group_name')
                ->where(['store_id' => $this->store->id, 'is_delete' => 0])
                ->asArray()->all();
            foreach ($attr_group_list as $i => $g) {
                $attr_list = Attr::find()
                    ->select('id attr_id,attr_name')
                    ->where(['attr_group_id' => $g['attr_group_id'], 'is_delete' => 0, 'is_default' => 0])
                    ->asArray()->all();
                if (empty($attr_list)) {
                    unset($attr_group_list[$i]);
                } else {
                    $goods_attr_list = json_decode($goods->attr, true);
                    if (!$goods_attr_list) {
                        $goods_attr_list = [];
                    }

                    foreach ($attr_list as $j => $attr) {
                        $checked = false;
                        foreach ($goods_attr_list as $goods_attr) {
                            foreach ($goods_attr['attr_list'] as $g_attr) {
                                if ($attr['attr_id'] == $g_attr['attr_id']) {
                                    $checked = true;
                                    break;
                                }
                            }
                            if ($checked) {
                                break;
                            }
                        }
                        $attr_list[$j]['checked'] = $checked;
                    }
                    $attr_group_list[$i]['attr_list'] = $attr_list;
                }
            }
            $new_attr_group_list = [];
            foreach ($attr_group_list as $item) {
                $new_attr_group_list[] = $item;
            }

            return $this->render('goods-attr', [
                'goods' => $goods,
                'attr_group_list' => $new_attr_group_list,
                'checked_attr_list' => $goods->attr,
            ]);
        }
    }

    /**
     * 一键采集
     */
    public function actionCopy()
    {
        $form = new CopyForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->copy();
    }

    /**
     * 淘宝采集
     */
    public function actionTcopy()
    {
        $form = new CopyForm();
        $html = \Yii::$app->request->post('html');
        return $form->t_copy_2($html);
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
        $msg = '请刷新重试';
        if ($get['type'] == 0) { //批量上架
            $res = Goods::updateAll(['status' => 1], $condition);
            $msg = '商品库存为0，无法上架';
        } elseif ($get['type'] == 1) { //批量下架
            $res = Goods::updateAll(['status' => 0], $condition);
        } elseif ($get['type'] == 2) { //批量删除
            $res = Goods::updateAll(['is_delete' => 1], $condition);
        } elseif ($get['type'] == 3) { //批量加入快速购买
            $res = Goods::updateAll(['quick_purchase' => 1], $condition);
        } elseif ($get['type'] == 4) { //批量关闭快速购买
            $res = Goods::updateAll(['quick_purchase' => 0], $condition);
        }
        if ($res > 0) {
            return [
                'code' => 0,
                'msg' => '设置成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => $msg
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

        $integral = \Yii::$app->serializer->encode($integral);

        if (empty($get['goods_group'])) {
            return [
                'code' => 1,
                'msg' => '请选择商品',
            ];
        }
        $res = Goods::updateAll(['integral' => $integral], ['in', 'id', $get['goods_group']]);
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

    /**
     * 设置商品排序
     */
    public function actionSetSort()
    {
        $form = new SetGoodsSortForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->save();
    }

    /**
     * @param int $mall_id
     * 拉取商城商品数据
     */
    public function actionGoodsCopy($mall_id = 0)
    {
        $goods = Goods::findOne(['id' => $mall_id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在,或已删除',
            ];
        }

        $goodsPic = GoodsPic::find()->select('pic_url')->andWhere(['goods_id' => $goods->id, 'is_delete' => 0])->asArray()->column();

        return [
            'code' => 0,
            'msg' => '成功',
            'data' => [
                'name' => $goods->name,
                'virtual_sales' => $goods->virtual_sales,
                'original_price' => $goods->original_price,
                'price' => $goods->price,
                'pic' => $goodsPic,
                'cover_pic' => $goods->cover_pic,
                'unit' => $goods->unit,
                'weight' => $goods->weight,
                'detail' => $goods->detail,
                'service' => $goods->service,
                'sort' => $goods->sort,
                'freight' => $goods->freight,
                'attr_group_list' => \Yii::$app->serializer->encode($goods->getAttrData()),
                'checked_attr_list' => \Yii::$app->serializer->encode($goods->getCheckedAttrData()),
                'use_attr' => $goods->use_attr,
                'attr' => $goods->attr,
            ],
        ];
    }

    // 淘宝CSV上传
    public function actionTaobaoCopy()
    {
        if (\Yii::$app->request->isPost) {
            $form = new Taobaocsv();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $res = $form->search();
            return $res;
        }
        return $this->render('taobao-copy');
    }

    /**
     * 商城商品列表
     * @return array
     */
    public function actionGoodsSearch()
    {
        $model = new GoodsSearchForm();
        $model->keyword = \Yii::$app->request->get('keyword');
        $data = $model->goodsSearch();

        return $data;
    }

    public function actionUpdateGoodsName()
    {
        $data = \Yii::$app->request->post();

        if (empty($data['goodsType'])) {
            return [
                'code' => 1,
                'msg' => '请输入参数OrderType'
            ];
        }

        if (empty($data['goodsId'])) {
            return [
                'code' => 1,
                'msg' => '请传入商品ID'
            ];
        }

        if (empty($data['goodsName'])) {
            return [
                'code' => 1,
                'msg' => '请填写商品名称'
            ];
        }


        switch ($data['goodsType']) {
            case 'STORE':
                $goods = Goods::findOne($data['goodsId']);
                break;
            case 'MIAOSHA':
                $goods = MsGoods::findOne($data['goodsId']);
                break;
            case 'PINTUAN':
                $goods = PtGoods::findOne($data['goodsId']);
                break;
            case 'BOOK':
                $goods = YyGoods::findOne($data['goodsId']);
                break;
            default:
                return [
                    'code' => 1,
                    'msg' => 'goodsType参数不是预期的'
                ];
                break;
        }

        $goods->name = $data['goodsName'];
        $goods->save();

        return [
            'code' => 0,
            'msg' => '修改成功'
        ];
    }
}