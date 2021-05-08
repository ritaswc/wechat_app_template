<?php

/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/8
 * Time: 14:22
 */

namespace app\modules\mch\controllers\integralmall;

use app\models\Banner;
use app\models\Cat;
use app\models\Coupon;
use app\models\Express;
use app\models\Goods;
use app\models\GoodsCat;
use app\models\GoodsPic;
use app\models\IntegralCat;
use app\models\IntegralGoods;
use app\models\IntegralOrder;
use app\models\IntegralSetting;
use app\models\Model;
use app\models\Option;
use app\models\PostageRules;
use app\models\User;
use app\models\UserCoupon;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\BannerForm;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\integralmall\CouponForm;
use app\modules\mch\models\integralmall\IntegralCatForm;
use app\modules\mch\models\integralmall\IntegralGoodsForm;
use app\modules\mch\models\integralmall\IntegralOrderForm;
use app\modules\mch\models\integralmall\SettingForm;
use app\modules\mch\models\integralmall\WechatTplMsgSender;
use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\order\OrderClerkForm;
use app\modules\mch\models\order\OrderDeleteForm;
use app\modules\mch\models\PrintForm;
use app\utils\TaskCreate;
use yii\data\Pagination;

class IntegralmallController extends Controller
{
    public $keyword;

    //积分商城设置
    public function actionSetting()
    {
        $setting = IntegralSetting::findOne(['store_id' => $this->store->id]);
        if (!$setting) {
            $setting = new IntegralSetting();
        }
        if (\Yii::$app->request->isPost) {
            $attr = \Yii::$app->request->post('attr');
            $model = \Yii::$app->request->post('model');
            $model['integral_shuoming'] = \Yii::$app->serializer->encode($attr);
            $form = new SettingForm();
            $form->store_id = $this->store->id;
            $form->attributes = $model;
            $form->setting = $setting;
            return $form->save();
        } else {
            return $this->render('setting', [
                'setting' => $setting,
                'integral_list' => json_decode($setting->integral_shuoming),
            ]);
        }
    }

    //积分说明删除
    public function actionAttrDelete()
    {
        $id = \Yii::$app->request->get('id');
        $setting = IntegralSetting::findOne(['store_id' => $this->store->id]);
        if ($setting && $setting->integral_shuoming) {
            $shuoming = \Yii::$app->serializer->decode($setting->integral_shuoming);
            $newList = [];
            foreach ($shuoming as $key => $value) {
                if ($key == $id) {
                } else {
                    $newList[] = $value;
                }
            }
            $setting->integral_shuoming = \Yii::$app->serializer->encode($newList);
        }
        if ($setting->save()) {
            return [
                'code' => 0,
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    //积分说明修改
    public function actionAttrEdit()
    {
        $get = \Yii::$app->request->get();
        $setting = IntegralSetting::findOne(['store_id' => $this->store->id]);
        if ($setting && $setting->integral_shuoming) {
            $shuoming = \Yii::$app->serializer->decode($setting->integral_shuoming);
            foreach ($shuoming as $key => &$value) {
                if ($key == $get['index']) {
                    $value['title'] = $get['title'];
                    $value['content'] = $get['content'];
                }
            }
            $setting->integral_shuoming = \Yii::$app->serializer->encode($shuoming);
        }
        if ($setting->save()) {
            return [
                'code' => 0,
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }
    }

    //轮播图设置
    public function actionSlide()
    {
        $list = Banner::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'type' => 3])->orderBy('sort ASC')->all();
        return $this->render('slide', [
            'list' => $list,
        ]);
    }

    //轮播图编辑
    public function actionSlideEdit()
    {
        $id = \Yii::$app->request->get('id');
        $banner = Banner::find()->where(['store_id' => $this->store->id, 'id' => $id, 'is_delete' => 0, 'type' => 3])->one();
        if (!$banner) {
            $banner = new Banner();
        }
        $form = new BannerForm();
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $model['store_id'] = $this->store->id;
            $form->attributes = $model;
            $form->banner = $banner;
            return $form->save();
        }
        foreach ($banner as $index => $value) {
            $banner[$index] = str_replace("\"", "&quot;", $value);
        }
        return $this->render('slide-edit', [
            'list' => $banner,
        ]);
    }

    // 轮播图删除
    public function actionSlideDel($id = 0)
    {
        $id = \Yii::$app->request->get('id');
        $banner = Banner::find()->where(['store_id' => $this->store->id, 'id' => $id, 'is_delete' => 0, 'type' => 3])->one();
        if (!$banner) {
            return [
                'code' => 1,
                'msg' => '轮播图不存在或已经删除',
            ];
        }
        $banner->is_delete = 1;
        if ($banner->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($banner->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    // 分类展示
    public function actionCat()
    {
        $form = new IntegralCatForm();
        $arr = $form->getList($this->store->id);
        return $this->render('cat', [
            'list' => $arr[0],
            'pagination' => $arr[1],
        ]);
    }

    // 分类编辑
    public function actionCatEdit($id = null)
    {
        $cat = IntegralCat::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cat) {
            $cat = new IntegralCat();
        }
        if (\Yii::$app->request->isPost) {
            $form = new IntegralCatForm();
            $form->attributes = \Yii::$app->request->post('model');
            $form->store_id = $this->store->id;
            $form->cat = $cat;
            return $form->save();
        }
        return $this->render('cat-edit', [
            'list' => $cat,
        ]);
    }

    // 分类删除
    public function actionCatDel($id = null)
    {
        $cat = IntegralCat::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cat) {
            return [
                'code' => 1,
                'msg' => '分类不存在或已删除',
            ];
        }
        $cat->is_delete = 1;
        if ($cat->save()) {
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

    // 商品展示
    public function actionGoods()
    {
        $form = new IntegralGoodsForm();
        $form->store_id = $this->store->id;
        $arr = $form->getList($this->store->id);
        return $this->render('goods', [
            'list' => $arr['0'],
            'pagination' => $arr['1'],
            'row_count' => $arr['2'],
        ]);
    }

    // 选择商城商品
    public function actionGoodsList($keyword = null, $status = null)
    {
        if (\Yii::$app->request->isPost) {
            $goods = new IntegralGoods();
            $form = new IntegralGoodsForm();
            $form->attr = \Yii::$app->request->post('attr');
            $model = \Yii::$app->request->post('model');
            $model['goods'] = \Yii::$app->serializer->decode($model['goods']);
            $model['goods']->goods_pic_list = $model['goods_pic'];
            $model['goods']->store_id = $this->store->id;
            $model['goods']->cat_id = $model['cat_id'];
            $model['goods']->price = $model['price'];
            $model['goods']->user_num = $model['user_num'];
            $model['goods']->integral = $model['integral'];
            $model['goods']->sort = $model['sort'];
            $form->model = $model['goods'];
            $form->goods = $goods;
            return $form->addGoods();
        }
        $query_cat = GoodsCat::find()->alias('gc')->leftJoin(['c' => Cat::tableName()], 'c.id=gc.cat_id')
            ->where(['gc.store_id' => $this->store->id, 'gc.is_delete' => 0])->select('gc.goods_id,c.name,gc.cat_id');
        $query = Goods::find()->alias('g')->where(['g.store_id' => $this->store->id, 'g.is_delete' => 0, 'g.mch_id' => 0]);

        if ($status != null) {
            $query->andWhere('g.status=:status', [':status' => $status]);
        }
        $query->leftJoin(['c' => Cat::tableName()], 'c.id=g.cat_id');
        $query->leftJoin(['gc' => $query_cat], 'gc.goods_id=g.id');

        $cat_query = clone $query;

        $query->select('g.id,g.name,g.price,g.original_price,g.status,g.cover_pic,g.sort,g.attr,g.cat_id,g.virtual_sales,g.store_id,g.quick_purchase');
        if (trim($keyword)) {
            $query->andWhere(['LIKE', 'g.name', $keyword]);
        }
        if (isset($_GET['cat'])) {
            $cat = trim($_GET['cat']);
            $query->andWhere([
                'or',
                ['c.name' => $cat],
                ['gc.name' => $cat],
            ]);
        }
        $cat_list = $cat_query->groupBy('name')->orderBy(['g.cat_id' => SORT_ASC])->select([
            '(case when g.cat_id=0 then gc.name else c.name end) name',
        ])->asArray()->column();

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $list = $query->groupBy('g.id')->orderBy('g.sort ASC,g.addtime DESC')
            ->limit($pagination->limit)->offset($pagination->offset)->all();
        $cat = IntegralCat::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store->id])
            ->asArray()
            ->orderBy('sort ASC')
            ->all();
        return $this->render('goods-list', [
            'list' => $list,
            'pagination' => $pagination,
            'cat_list' => $cat_list,
            'cat' => $cat,
        ]);
    }

    //商城商品详情
    public function actionGoodsInfo()
    {
        $id = \Yii::$app->request->get('id');
        $goods = Goods::find()->where(['store_id' => $this->store->id, 'id' => $id, 'is_delete' => 0, 'mch_id' => 0])->one();
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已删除',
            ];
        }
        $goods_info = Goods::find()
            ->select(['name', 'price as original_price', 'detail', 'service', 'virtual_sales', 'cover_pic', 'unit', 'weight', 'freight', 'use_attr', 'goods_num'])
            ->where(['id' => $id])->asArray()->one();

        $goods_pic = GoodsPic::find()
            ->select('pic_url')
            ->where(['goods_id' => $id, 'is_delete' => 0])->asArray()->all();
        $goods_pic = array_column($goods_pic, 'pic_url');
        $attr = $goods->getAttrData();
        $checked_attr_list = $goods->getCheckedAttrData();
        return [
            'code' => 0,
            'list' => [
                'goods_info' => \Yii::$app->serializer->encode($goods_info),
                'goods_pic' => \Yii::$app->serializer->encode($goods_pic),
                'attr' => $attr,
                'checked_attr_list' => $checked_attr_list,
            ],
        ];
    }

    // 商品编辑
    public function actionGoodsEdit($id = null)
    {
        $goods = IntegralGoods::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$goods) {
            $goods = new IntegralGoods();
        }
        $form = new IntegralGoodsForm();
        $cat = IntegralCat::find()
            ->andWhere(['is_delete' => 0, 'store_id' => $this->store->id])
            ->asArray()
            ->orderBy('sort ASC')
            ->all();
        $postageRiles = PostageRules::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->all();
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            if (!$model['use_attr']) {
                $model['use_attr'] = 0;
            }
            $model['store_id'] = $this->store->id;

            $form->attributes = $model;
            $form->attr = \Yii::$app->request->post('attr');
            $form->goods = $goods;
            return $form->save();
        }
        if ($goods && $goods->goods_pic_list) {
            $goods->goods_pic_list = \Yii::$app->serializer->decode($goods->goods_pic_list);
        }

        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();


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

        $goodsNum = 0;
        if (isset($goods['attr'])) {
            $attrs = \Yii::$app->serializer->decode($goods['attr']);
            foreach ($attrs as $attr) {
                $goodsNum += $attr['num'];
            }
            $goods['goods_num'] = $goodsNum;
        }

        return $this->render('goods-edit', [
            'goods' => $goods,
            'cat' => $cat,
            'levelList' => $levelList,
            'postageRiles' => $postageRiles,
        ]);
    }

    //商品上下架
    public function actionGoodsUpDown($id = null, $type = null)
    {
        if ($type == 'down') {
            $goods = IntegralGoods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 1, 'store_id' => $this->store->id]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已下架',
                ];
            }
            $goods->status = 0;
        } elseif ($type == 'up') {
            $goods = IntegralGoods::findOne(['id' => $id, 'is_delete' => 0, 'status' => 0, 'store_id' => $this->store->id]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '商品已删除或已上架',
                ];
            }
            if (!$goods->getNum()) {
                $return_url = \Yii::$app->urlManager->createUrl(['mch/integralmall/integralmall/goods-edit', 'id' => $goods->id]);
                if (!$goods->use_attr) {
                    $return_url = \Yii::$app->urlManager->createUrl(['mch/integralmall/integralmall/goods-edit', 'id' => $goods->id]) . '#step3';
                }

                return [
                    'code' => 1,
                    'msg' => '商品库存不足，请先完善商品库存',
                    'return_url' => $return_url,
                ];
            }
            $goods->status = 1;
        } elseif ($type == 'no-index') {
            $goods = IntegralGoods::findOne(['id' => $id, 'is_delete' => 0, 'is_index' => 1, 'store_id' => $this->store->id, 'status' => 1]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '请先上架，或商品已删除或已取消放置首页',
                ];
            }
            $goods->is_index = 0;
        } elseif ($type == 'index') {
            $goods = IntegralGoods::findOne(['id' => $id, 'is_delete' => 0, 'is_index' => 0, 'store_id' => $this->store->id, 'status' => 1]);
            if (!$goods) {
                return [
                    'code' => 1,
                    'msg' => '请先上架，或商品已删除或已放置首页',
                ];
            }
            $goods->is_index = 1;
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

    //商品删除
    public function actionGoodsDel($id = 0)
    {
        $goods = IntegralGoods::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
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

    //优惠券列表
    public function actionCoupon()
    {
        $form = new CouponForm();
        $keyword = \Yii::$app->request->get('keyword');
        $arr = $form->getList($this->store->id, $keyword);
        return $this->render('coupon', [
            'list' => $arr[0],
            'pagination' => $arr[1],
        ]);
    }

    //选择优惠卷
    public function actionCouponList()
    {
        $form = new CouponForm();
        $keyword = \Yii::$app->request->get('keyword');
        $arr = $form->getList2($this->store->id, $keyword);
        return $this->render('coupon', [
            'list' => $arr[0],
            'pagination' => $arr[1],
        ]);
    }

    //添加优惠券到积分商城
    public function actionInCouponAdd()
    {
        $get = \Yii::$app->request->post('arr');
        $coupon = Coupon::findOne([
            'id' => $get['coupon_id'],
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$coupon) {
            return [
                'code' => 1,
                'msg' => '优惠券不存在或已删除',
            ];
        }
        $coupon->is_integral = 2;
        $coupon->integral = $get['integral'];
        $coupon->total_num = $get['total_num'];
        $coupon->user_num = $get['user_num'];
        $coupon->price = $get['price'];
        if ($coupon->save()) {
            return [
                'code' => 0,
                'msg' => '添加成功',
            ];
        }
    }

    //删除优惠券
    public function actionInCouponDel()
    {
        $id = \Yii::$app->request->get('id');
        $coupon = Coupon::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'is_integral' => 2,
        ]);
        if (!$coupon) {
            return [
                'code' => 1,
                'msg' => '优惠券不存在或已删除',
            ];
        }
        $coupon->is_integral = 1;
        $coupon->integral = 0;
        $coupon->total_num = 0;
        $coupon->user_num = 0;
        $coupon->price = 0;
        if ($coupon->save()) {
            return [
                'code' => 0,
            ];
        }
    }

    //用户优惠券
    public function actionUserCoupon()
    {
        $keyword = \Yii::$app->request->get('keyword');
        if ($keyword) {
            $this->keyword = trim($keyword);
        } else {
            $this->keyword = '';
        }
        $query = UserCoupon::find()
            ->where([
                'store_id' => $this->store->id,
                'is_delete' => 0,
                'type' => 3,
            ])->with(['coupon' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 1,
                ])->andWhere(['LIKE', 'name', $this->keyword]);
            }])->with(['user' => function ($query) {
                $query->where([
                    'store_id' => $this->store->id,
                    'is_delete' => 0,
                ]);
            }]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 15]);
        $userCoupon = $query
            ->offset($p->offset)
            ->limit($p->limit)
            ->asArray()
            ->all();
        foreach ($userCoupon as $k => &$v) {
            if (empty($v['coupon'])) {
                unset($userCoupon[$k]);
            }
        }

        return $this->render('user-coupon', [
            'list' => $userCoupon,
            'pagination' => $p,
        ]);
    }

    /**
     * 订单列表
     * @return string
     */
    public function actionOrder()
    {
        $f = new ExportList();
        $f->order_type = 4;
        $f->type = 0;
        $exportList = $f->getList();
        $form = new IntegralOrderForm();
        $form->attributes = \Yii::$app->request->get();
        $form->platform = \Yii::$app->request->get('platform');
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->limit = 10;
        $arr = $form->search();
        return $this->render('order', [
            'list' => $arr[0],
            'pagination' => $arr[1],
            'row_count' => $arr[2],
            'express_list' => $this->getExpressList(),
            'exportList' => \Yii::$app->serializer->encode($exportList)
        ]);
    }

    //订单快递
    private function getExpressList()
    {
        $storeExpressList = IntegralOrder::find()
            ->select('express')
            ->where([
                'AND',
                ['store_id' => $this->store->id],
                ['is_send' => 1],
                ['!=', 'express', ''],
            ])->groupBy('express')->orderBy('send_time DESC')->limit(5)->asArray()->all();
        $expressLst = Express::getExpressList();
        $newStoreExpressList = [];
        foreach ($storeExpressList as $i => $item) {
            foreach ($expressLst as $value) {
                if ($value['name'] == $item['express']) {
                    $newStoreExpressList[] = $item['express'];
                    break;
                }
            }
        }

        $newPublicExpressList = [];
        foreach ($expressLst as $i => $item) {
            $newPublicExpressList[] = $item['name'];
        }

        return [
            'private' => $newStoreExpressList,
            'public' => $newPublicExpressList,
        ];
    }

    //订单打印
    public function actionPrint()
    {
        $id = \Yii::$app->request->get('id');
        $express = \Yii::$app->request->get('express');
        $post_code = \Yii::$app->request->get('post_code');
        $form = new PrintForm();
        $form->store_id = $this->store->id;
        $form->order_id = $id;
        $form->express = $express;
        $form->post_code = $post_code;
        $form->order_type = 'IN';
        return $form->send();
    }

    //订单发货
    public function actionSend()
    {
        $form = new IntegralOrderForm();
        $post = \Yii::$app->request->post();
        $form->attributes = $post;
        $form->store_id = $this->store->id;
        return $form->save();
    }

    //订单详情
    public function actionDetail($order_id = null)
    {
        $form = new IntegralOrderForm();
        $form->store_id = $this->store->id;
        $form->order_id = $order_id;
        $order = $form->detail();
        return $this->render('detail', ['order' => $order]);
    }

    //订单取消同意拒绝
    public function actionApplyDeleteStatus($id, $status)
    {
        $order = IntegralOrder::findOne([
            'id' => $id,
            'apply_delete' => 1,
            'is_delete' => 0,
            'store_id' => $this->store->id,
            'mch_id' => 0,
        ]);
        if (!$order || $order->mch_id > 0) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新页面后重试',
            ];
        }
        if ($status == 1) { //同意
            $form = new IntegralOrderForm();
            $form->order_id = $order->id;
            $form->user_id = $order->user_id;
            $form->store_id = $order->store_id;
            $res = $form->agree();
            if ($res['code'] == 0) {
                $msg_sender = new WechatTplMsgSender($this->store->id, $order->id, $this->wechat);
                $msg_sender->revokeMsg('商家同意了您的订单取消请求');
                return [
                    'code' => 0,
                    'msg' => '操作成功',
                ];
            } else {
                return $res;
            }
        } else { //拒绝
            $order->apply_delete = 0;
            $order->save();
            $msg_sender = new WechatTplMsgSender($this->store->id, $order->id, $this->wechat);
            $msg_sender->revokeMsg('您的取消申请已被拒绝');
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        }
    }

    public function actionConfirm()
    {
        $order_id = \Yii::$app->request->get('order_id');
        $order = IntegralOrder::findOne(['id' => $order_id]);
        if ($order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新重试',
            ];
        }
        if ($order->pay_type != 2) {
            return [
                'code' => 1,
                'msg' => '订单支付方式不是货到付款，无法确认收货',
            ];
        }
        if ($order->is_send == 0) {
            return [
                'code' => 1,
                'msg' => '订单未发货',
            ];
        }
        $order->is_confirm = 1;
        $order->confirm_time = time();
        $order->is_pay = 1;
        $order->pay_time = time();
        if ($order->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($order->errors as $error) {
                return [
                    'code' => 1,
                    'msg' => $error,
                ];
            }
        }
    }

    // 删除订单（软删除）
    public function actionDelete($order_id = null)
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\IntegralOrder';
        $orderDeleteForm->order_id = $order_id;
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->delete();
    }

    // 清空回收站
    public function actionDeleteAll()
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\IntegralOrder';
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->deleteAll();
    }

    // 移入移出回收站
    public function actionRecycle($order_id = null, $is_recycle = 0)
    {
        $orderDeleteForm = new OrderDeleteForm();
        $orderDeleteForm->order_model = 'app\models\IntegralOrder';
        $orderDeleteForm->order_id = $order_id;
        $orderDeleteForm->is_recycle = $is_recycle;
        $orderDeleteForm->store = $this->store;
        return $orderDeleteForm->recycle();
    }

    // 核销订单
    public function actionClerk()
    {
        $form = new OrderClerkForm();
        $form->attributes = \Yii::$app->request->get();
        $form->order_model = 'app\models\IntegralOrder';
        $form->order_type = 4;
        $form->store = $this->store;
        return $form->clerk();
    }
}
