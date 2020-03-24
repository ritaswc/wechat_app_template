<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/28
 * Time: 15:21
 */


namespace app\modules\api\controllers\mch;

use app\models\Goods;
use app\models\Mch;
use app\models\MchCat;
use app\models\MchGoodsCat;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\Model;
use yii\data\Pagination;

class ShopDataForm extends Model
{
    public $mch_id;
    public $tab;
    public $sort;
    public $page;
    public $limit;
    public $cat_id;

    public function rules()
    {
        return [
            ['mch_id', 'required'],
            [['mch_id', 'cat_id',], 'integer'],
            ['tab', 'required'],
            ['sort', 'safe'],
            ['page', 'default', 'value' => 1,],
            ['limit', 'default', 'value' => 20,],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $shop = [];
        $coupon_list = [];
        $banner_list = [];
        $goods_list = [];
        $new_list = [];

        $shop = $this->getShop();
        if (isset($shop['code']) && $shop['code'] == 1) {
            return $shop;
        }

        if ($this->tab == 1) {
            $coupon_list = $this->getCouponList();
            $goods_list = $this->getHotList();
        }
        if ($this->tab == 2) {
            $goods_list = $this->getGoodsList();
        }
        if ($this->tab == 3) {
            $new_list = $this->getNewList();
        }

        return [
            'code' => 0,
            'data' => [
                'shop' => $shop,
                'coupon_list' => $coupon_list,
                'banner_list' => $banner_list,
                'goods_list' => $goods_list,
                'new_list' => $new_list,
            ],
        ];
    }

    public function getShop()
    {
        $mch = Mch::findOne([
            'id' => $this->mch_id,
            'review_status' => 1,
            'is_delete' => 0,
        ]);
        if (!$mch) {
            return [
                'code' => 1,
                'msg' => '店铺不存在',
            ];
        }
        if ($mch->is_open == 0 || $mch->is_lock == 1) {
            return [
                'code' => 1,
                'msg' => '店铺打烊了~',
            ];
        }
        $shop = [
            'id' => $mch->id,
            'name' => $mch->name,
            'logo' => $mch->logo ? $mch->logo : \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/shop/img/shop-logo.png',
            'header_bg' => $mch->header_bg ? $mch->header_bg : \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/shop/img/shop-header-bg.jpg',
            'goods_num' => $this->getGoodsNum(),
            'sale_num' => $this->getSaleNum(),
        ];
        return $shop;
    }

    public function getCouponList()
    {
        return [
        ];
    }

    public function getHotList()
    {
        //有设置热销的优先返回设置热销的
        $query = Goods::find()->alias('g')->where([
            'g.is_delete' => 0,
            'g.mch_id' => $this->mch_id,
            'g.status' => 1,
            'g.hot_cakes' => 1,
        ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $query->limit($pagination->limit)->offset($pagination->offset);
        $list = $query->select('g.id,g.name,g.cover_pic,g.price')->orderBy('g.mch_sort,g.addtime DESC')->asArray()->all();
        if (is_array($list) && count($list)) {
            return $list;
        }

        //没有热销的按销量排序
        $query = Goods::find()->alias('g')
            ->leftJoin(['od' => OrderDetail::find()->select('goods_id,SUM(num) sale_num')], 'g.id=od.goods_id')
            ->where([
                'g.is_delete' => 0,
                'g.mch_id' => $this->mch_id,
                'g.status' => 1,
            ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $query->limit($pagination->limit)->offset($pagination->offset);

        $list = $query->select('g.id,g.name,g.cover_pic,g.price')->orderBy('od.sale_num DESC,g.mch_sort,g.addtime DESC')->asArray()->all();
        if (is_array($list) && count($list)) {
            return $list;
        }
        return [];
    }

    public function getGoodsList()
    {
        $query = Goods::find()->alias('g')->where([
            'g.is_delete' => 0,
            'g.mch_id' => $this->mch_id,
            'g.status' => 1,
        ]);
        if ($this->sort == 0) {
            $query->orderBy('g.mch_sort,g.addtime DESC');
            if ($this->cat_id) {
                $query->leftJoin(['mgc' => MchGoodsCat::tableName()], 'mgc.goods_id=g.id')->andWhere([
                    'mgc.cat_id' => MchCat::find()->alias('mc')->select('id')->where([
                        'OR',
                        ['parent_id' => $this->cat_id],
                        ['id' => $this->cat_id],
                    ])
                ]);
                $query->groupBy('g.id');
            }
        }
        if ($this->sort == 1) {
            $query->orderBy('g.mch_sort,g.addtime DESC');
        }
        if ($this->sort == 2) {
            $query->leftJoin(['od' => OrderDetail::find()->select('goods_id,SUM(num) sale_num')], 'g.id=od.goods_id');
            $query->orderBy('od.sale_num DESC,g.mch_sort,g.addtime');
        }
        if ($this->sort == 3) {
            $query->orderBy('g.price,g.mch_sort,g.addtime DESC');
        }
        if ($this->sort == 4) {
            $query->orderBy('g.price DESC,g.mch_sort,g.addtime DESC');
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $query->limit($pagination->limit)->offset($pagination->offset);
        $list = $query->select('g.id,g.name,g.cover_pic,g.price')->asArray()->all();
        if (is_array($list) && count($list)) {
            return $list;
        }
        return [];
    }

    public function getNewList()
    {
        $query = Goods::find()->alias('g')->where([
            'g.is_delete' => 0,
            'g.mch_id' => $this->mch_id,
            'g.status' => 1,
        ])->andWhere(['>=', 'g.addtime', time() - 86400 * 60]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => 50,]);
        $query->limit($pagination->limit)->offset($pagination->offset);
        $list = $query->select('g.id,g.name,g.cover_pic,g.price,g.addtime')->orderBy('g.addtime DESC')->asArray()->all();
        $new_list = [];
        foreach ($list as $item) {
            $date = date('m月d日', $item['addtime']);
            if (empty($new_list[$date])) {
                $new_list[$date] = [];
            }
            $new_list[$date][] = $item;
        }
        $new_list2 = [];
        foreach ($new_list as $date => $item) {
            $new_list2[] = [
                'date' => $date,
                'list' => $new_list[$date],
            ];
        }
        return $new_list2;
    }

    public function getGoodsNum($format = true)
    {
        $count = Goods::find()->where(['mch_id' => $this->mch_id, 'is_delete' => 0, 'status' => 1,])->count('1');
        $count = $count ? $count : 0;
        if ($count >= 10000 && $format) {
            $count = sprintf('%.2f', $count) . '万';
        }
        return $count;
    }

    public function getSaleNum($format = true)
    {
        $count = OrderDetail::find()->alias('od')
            ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
            ->where([
                'o.is_pay' => 1,
                'o.mch_id' => $this->mch_id,
            ])
            ->sum('od.num');
        $count = $count ? $count : 0;
        if ($count >= 10000 && $format) {
            $count = sprintf('%.2f', $count) . '万';
        }
        return $count;
    }
}
