<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/11
 * Time: 9:38
 */

namespace app\modules\mch\controllers;

use app\models\GoodsSearchForm;
use app\models\GoodsShare;
use app\models\Miaosha;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\MsSetting;
use app\modules\mch\models\LevelListForm;
use app\modules\mch\models\miaosha\GoodsDetailEditForm;
use app\modules\mch\models\MiaoshaCalendar;
use app\modules\mch\models\MiaoshaDateForm;
use app\modules\mch\models\MiaoshaGoodsEditForm;
use yii\data\Pagination;

class MiaoshaController extends Controller
{
    public function actionIndex()
    {
        $model = Miaosha::findOne([
            'store_id' => $this->store->id,
        ]);
        if (!$model) {
            $model = new Miaosha();
            $model->store_id = $this->store->id;
        }
        if (\Yii::$app->request->isPost) {
            $model->open_time = \Yii::$app->serializer->encode((array)\Yii::$app->request->post('open_time', []));
            $model->save();
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    public function actionGoods()
    {
        $list = MiaoshaGoods::find()->alias('mg')
            ->leftJoin(['g' => MsGoods::tableName()], 'g.id=mg.goods_id')
            ->where(['mg.store_id' => $this->store->id, 'mg.is_delete' => 0, 'g.is_delete' => 0])->groupBy('mg.goods_id')
            ->select('g.name,mg.*,COUNT(mg.goods_id) miaosha_count')->asArray()->all();
        return $this->render('goods', [
            'list' => $list,
        ]);
    }

    public function actionGoodsEdit()
    {
        $miaoshaGoods = new MiaoshaGoods();
        $miaosha = Miaosha::findOne([
            'store_id' => $this->store->id,
        ]);

        $goodsId = \Yii::$app->request->post('goods_id');
        $goods = MsGoods::findOne(['id' => $goodsId, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$goods) {
            $goods = new MsGoods();
        }

        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();

        if (!$miaosha) {
            $miaosha = new Miaosha();
            $miaosha->store_id = $this->store->id;
            $miaosha->open_time = "[]";
        }
        if (\Yii::$app->request->isPost) {
            $form = new MiaoshaGoodsEditForm();
            $model = \Yii::$app->request->post('model');

            $form->attributes = \Yii::$app->request->post();
            $form->attributes = $model;
            $form->store_id = $this->store->id;
            $form->miaoshaGoods = $miaoshaGoods;
            $form->goods = $goods;
            $form->stock = $goods->getNum($goodsId);
            $form->attr = \Yii::$app->request->post('attr');

            $attr_member_price_List = [];
            foreach ($levelList as $level) {
                $keyName = 'member' . $level['level'];
                $attr_member_price_List[$keyName] = \Yii::$app->request->post($keyName);
            }
            $form->attr_member_price_List = $attr_member_price_List;
            $res = $form->save();

            return $res;

        } else {

            return $this->render('goods-edit', [
                'model' => $miaoshaGoods,
                'miaosha' => $miaosha,
                'levelList' => $levelList,
            ]);
        }
    }

    public function actionGoodsSearch($keyword = null, $page = 1)
    {
        $form = new GoodsSearchForm();
        $form->keyword = $keyword;
        $form->page = $page;
        $form->store_id = $this->store->id;
        return $form->search();
    }

    public function actionGoodsDetail($goods_id)
    {
        $page = \Yii::$app->request->get('page');
        $date_begin = \Yii::$app->request->get('date_begin', date('Y-m-d', strtotime('-30 days')));
        $date_end = \Yii::$app->request->get('date_end', date('Y-m-d'));
        $query = MiaoshaGoods::find()->alias('mg')->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
            ->where(['mg.goods_id' => $goods_id, 'mg.is_delete' => 0])->asArray()->select('mg.*,g.name')->orderBy('mg.open_date ASC,mg.start_time ASC');

        $query->andWhere([
            'AND',
            ['>=', 'mg.open_date', $date_begin],
            ['<=', 'mg.open_date', $date_end],
        ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $page - 1, 'pageSize' => 10]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->all();

        return $this->render('goods-detail', [
            'list' => $list,
            'count' => $count ? $count : 0,
            'date_begin' => $date_begin,
            'date_end' => $date_end,
            'pagination' => $pagination,
        ]);
    }

    //删除单个秒杀记录
    public function actionMiaoshaDelete($id)
    {
        MiaoshaGoods::updateAll(['is_delete' => 1], [
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    /**
     * @param $id
     * @param $index
     * @param $price
     * 秒杀价格修改
     */
    public function actionMiaoshaPriceEdit($id, $index, $price)
    {
        $miaosha = MiaoshaGoods::findOne($id);
        $attr = json_decode($miaosha->attr, true);
        $attr[$index]['miaosha_price'] = $price;

        MiaoshaGoods::updateAll([
            'attr' => \Yii::$app->serializer->encode($attr),
        ], [
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    /**
     * @param $id
     * @param $index
     * @param $price
     * 秒杀数量修改
     */
    public function actionMiaoshaNumEdit($id, $index, $num)
    {
        $miaosha = MiaoshaGoods::findOne($id);
        $attr = json_decode($miaosha->attr, true);
        $attr[$index]['miaosha_num'] = $num;

        MiaoshaGoods::updateAll([
            'attr' => \Yii::$app->serializer->encode($attr),
        ], [
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //删除该商品的所有秒杀记录
    public function actionGoodsDelete($goods_id)
    {
        MiaoshaGoods::updateAll(['is_delete' => 1], [
            'goods_id' => $goods_id,
            'store_id' => $this->store->id,
        ]);
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //秒杀商品（日历视图）
    public function actionCalendar()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MiaoshaCalendar();
            $form->attributes = \Yii::$app->request->get();
            $form->store_id = $this->store->id;
            $res = $form->search();
            return $res;
        } else {
            return $this->render('calendar', []);
        }
    }

    //秒杀日期商品列表
    public function actionDate()
    {
        $form = new MiaoshaDateForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $res = $form->search();
        $this->layout = false;
        return [
            'code' => 0,
            'data' => [
                'title' => $res['data']['date'] . '秒杀安排表',
                'content' => $this->render('date', $res['data']),
            ],
        ];
    }

    /**
     * @return string
     * 秒杀设置
     */
    public function actionSetting()
    {
        $setting = MsSetting::findOne(['store_id' => $this->store->id]);
        if (!$setting) {
            $setting = new MsSetting();
        }
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            if ($setting->isNewRecord) {
                $setting->store_id = $this->store->id;
            }
            if ($model['unpaid'] === '') {
                $model['unpaid'] = 1;
            }
            if ($model['unpaid'] < 0 || $model['unpaid'] > 2000000000) {
                return [
                    'code' => 1,
                    'msg' => '请设置正确时间',
                ];
            }
            $setting->attributes = $model;
            if ($setting->save()) {
                return [
                    'code' => 0,
                    'msg' => '保存成功',
                ];
            } else {
                return [
                    'code' => 0,
                    'msg' => '保存失败，请重试',
                ];
            }
        }
        return $this->render('setting', [
            'setting' => $setting,
        ]);
    }

    /**
     * 商品会员价、分销价详情（每个秒杀场次的信息）
     */
    public function actionGoodsDetailEdit()
    {
        $id = \Yii::$app->request->get('id');
        $levelForm = new LevelListForm();
        $levelList = $levelForm->getAllLevel();


        // 当前场次的秒杀商品
        $miaoshaGoods = MiaoshaGoods::find()->where(['id' => $id])->one();
        // 秒杀商品分销设置
        $goods_share = GoodsShare::findOne(['store_id' => $this->store->id, 'relation_id' => $id, 'type' => GoodsShare::SHARE_GOODS_TYPE_MS]);
        $msGoods = MsGoods::find()->where(['id' => $miaoshaGoods['goods_id']])->one();//秒杀商品

        if (\Yii::$app->request->isPost) {
            $modelData = \Yii::$app->request->post('model');
            $model = new GoodsDetailEditForm();
            $model->attributes = \Yii::$app->request->post();
            $model->is_level = $modelData['is_level'];
            $model->individual_share = $modelData['individual_share'];
            $model->attr_setting_type = $modelData['attr_setting_type'];
            $model->share_type = $modelData['share_type'];
            $model->share_commission_first = $modelData['share_commission_first'];
            $model->share_commission_second = $modelData['share_commission_second'];
            $model->share_commission_third = $modelData['share_commission_third'];
            $model->miaoshaGoods = $miaoshaGoods;
            $model->goodsShare = $goods_share;
            $model->msGoods = $msGoods;

            $res = $model->save();

            return $res;
        }

        // 前端字段需要统一
        $attr = json_decode($miaoshaGoods['attr'], true);
        foreach ($attr as &$item) {
            $item['price'] = $item['miaosha_price'];
            $item['num'] = $item['miaosha_num'];
        }

        $msGoods->attr = json_encode($attr); //规格要用设置设置的规格
        isset($goods_share) ? $goods_share->is_level = $miaoshaGoods['is_level'] : ''; //是否开启会员


        return $this->render('goods-detail-edit', [
            'levelList' => $levelList,
            'goods' => $msGoods,
            'goods_share' => $goods_share
        ]);
    }
}
