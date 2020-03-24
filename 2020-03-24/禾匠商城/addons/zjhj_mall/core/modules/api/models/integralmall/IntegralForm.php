<?php

/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/14
 * Time: 17:20
 */

namespace app\modules\api\models\integralmall;

use app\models\IntegralGoods;
use app\models\IntegralOrderDetail;
use app\models\IntegralSetting;
use app\models\Model;
use app\models\MsOrder;
use app\models\Order;
use app\models\Register;
use app\modules\api\models\ApiModel;
use app\models\Banner;
use app\models\Coupon;
use app\models\IntegralCat;
use app\models\IntegralCouponOrder;
use app\models\User;
use yii\data\Pagination;

class IntegralForm extends ApiModel
{
    public $user_id;
    public $store_id;
    public $today;
    public $limit;
    public $page;
    public $status;
    public $cat_id;

    public function rules()
    {
        return [
            [['today'], 'string'],
            [['page', 'limit'], 'integer'],
            [['limit'], 'default', 'value' => 10],
            [['status', 'cat_id'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'today' => '今日日期',
        ];
    }

    public function index()
    {
        $banner_list = Banner::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'type' => 3])->orderBy('sort ASC')->asArray()->all();
        foreach ($banner_list as $i => $banner) {
            if (!$banner['open_type']) {
                $banner_list[$i]['open_type'] = 'navigate';
            }
            if ($banner['open_type'] == 'wxapp') {
                $res = $this->getUrl($banner['page_url']);
                $banner_list[$i]['appId'] = $res[2];
                $banner_list[$i]['path'] = urldecode($res[4]);
            }
        }
        $coupon_list = Coupon::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_integral' => 2])->orderBy('sort ASC')->asArray()->all();
        $user = User::find()->select(['integral'])->where(['id' => $this->user_id, 'store_id' => $this->store_id])->asArray()->one();
        $date = date('Y/n/j', time());
        $today = Register::find()->where(['register_time' => $date, 'store_id' => $this->store_id, 'user_id' => $this->user_id])->asArray()->one();
        foreach ($coupon_list as $key => &$value) {
            $count = IntegralCouponOrder::find()->where(['user_id' => $this->user_id, 'is_pay' => 1, 'store_id' => $this->store_id, 'coupon_id' => $value['id']])->count();
            if ($count >= $value['user_num']) {
                $value['type'] = 1;
                $value['num'] = $count;
            } else {
                $value['num'] = $count;
                $value['type'] = 0;
            }
        }

        $goods_list = IntegralCat::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
            ])->with(['goods' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'status' => 1,
                    'is_delete' => 0,
                ])->orderBy('sort ASC');
            }])
            ->orderBy('sort ASC')
            ->asArray()->all();

        $catList = IntegralCat::find()->alias('c')
            ->where([
                'c.store_id' => $this->getCurrentStoreId(),
                'c.is_delete' => Model::IS_DELETE_FALSE,
            ])
            ->leftJoin(['g' => IntegralGoods::tableName()], 'c.id=g.cat_id')
            ->orderBy('sort')
            ->select('c.*, g.id as goods_id')
            ->asArray()->all();

        foreach ($catList as $k => $item) {
            if (!isset($item['goods_id']) || !$item['goods_id'] || empty($item['goods_id'])) {
                unset($catList[$k]);
            }
        }
        $catList = array_values($catList);

        return [
            'code' => 0,
            'data' => [
                'banner_list' => $banner_list,
                'coupon_list' => $coupon_list,
                'goods_list' => $goods_list,// TODO 下个版本可删除相关代码
                'cat_list' => $catList,
                'user' => $user,
                'today' => $today,
            ],
        ];
    }

    public function explain()
    {
        $setting = IntegralSetting::find()->where(['store_id' => $this->store_id])->asArray()->one();
        $setting['integral_shuoming'] = $setting['integral_shuoming'] ? \Yii::$app->serializer->decode($setting['integral_shuoming']) : [];
        $setting['register_rule'] = explode('，', $setting['register_rule']);
        if ($setting && is_array($setting['integral_shuoming'])) {
            foreach ($setting['integral_shuoming'] as $k => &$v) {
                $v['content'] = explode('，', $v['content']);
            }
        }
        $today = Register::find()->where(['register_time' => $this->today, 'store_id' => $this->store_id, 'user_id' => $this->user_id, 'type' => 1])->asArray()->one();
        $register = Register::find()->where(['store_id' => $this->store_id, 'user_id' => $this->user_id, 'type' => 1])->orderBy('addtime DESC')->asArray()->one();
        $registerTime = Register::find()->where(['store_id' => $this->store_id, 'user_id' => $this->user_id, 'type' => 1])->asArray()->all();
        $time = [];
        if ($registerTime) {
            foreach ($registerTime as $key => &$value) {
                $time[] = $value['register_time'];
            }
        }
        return [
            'code' => 0,
            'data' => [
                'setting' => $setting,
                'today' => $today,
                'register' => $register,
                'registerTime' => $time
            ],
        ];
    }

    public function exchange()
    {

        $list = User::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'id' => $this->user_id,
            ])->with(['userCoupon' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'type' => 3,
                ])->with(['coupon' => function ($query) {
                    $query->where([
                        'store_id' => $this->store_id,
                    ]);
                }]);
            }])->with(['goodsDetail' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0,
                ])->with(['order' => function ($query) {
                    $query->where([
                        'store_id' => $this->store_id,
                        'is_delete' => 0,
                    ]);
                }]);
            }])
            ->asArray()->all();

        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }

    public function detail()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Register::find()->where(['store_id' => $this->store_id, 'user_id' => $this->user_id]);
        if ($this->status == 2) {
            $query->andWhere(['<', 'integral', 0]);
        } else {
            $query->andWhere(['>', 'integral', 0]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->orderBy(['addtime' => SORT_DESC])->limit($pagination->limit)->offset($pagination->offset)->all();
        $newList = [];
        /* @var Register[] $list */
        foreach ($list as $k => &$v) {
            $newItem = [];
            $newItem['addtime'] = date('Y-m-d', $v['addtime']);
            switch ($v['type']) {
                case 1:
                    $newItem['content'] = "商城签到";
                    $newItem['content_1'] = "签到送积分";
                    break;
                case 3:
                    $newItem['content'] = "平台调整";
                    $newItem['content_1'] = "平台调整积分";
                    break;
                case 4:
                    /* @var Order $order */
                    $order = $v->order;
                    $newItem['content'] = "商城订单，订单号：" . $order->order_no;
                    $newItem['content_1'] = "购物抵扣积分";
                    break;
                case 5:
                    /* @var MsOrder $order */
                    $order = $v->msOrder;
                    $newItem['content'] = "秒杀订单，订单号：" . $order->order_no;
                    $newItem['content_1'] = "购物抵扣积分";
                    break;
                case 6:
                    /* @var Order $order */
                    $order = $v->order;
                    $newItem['content'] = "商城订单，订单号：" . $order->order_no;
                    $newItem['content_1'] = "积分退还";
                    break;
                case 7:
                    /* @var Order $order */
                    $order = $v->order;
                    $newItem['content'] = "商城订单，订单号：" . $order->order_no;
                    $newItem['content_1'] = "购物送积分";
                    break;
                case 8:
                    /* @var MsOrder $order */
                    $order = $v->msOrder;
                    $newItem['content'] = "秒杀订单，订单号：" . $order->order_no;
                    $newItem['content_1'] = "购物送积分";
                    break;
                case 9:
                    /* @var Order $order */
                    $order = $v->order;
                    $newItem['content'] = "商城订单，订单号：" . $order->order_no;
                    $newItem['content_1'] = "积分退还";
                    break;
                case 10:
                    /* @var Coupon $coupon */
                    $coupon = $v->userCoupon->coupon;
                    $newItem['content'] = $coupon->name;
                    $newItem['content_1'] = "积分兑换优惠券";
                    break;
                case 11:
                    /* @var IntegralOrderDetail $integralOrderDetail */
                    $integralOrderDetail = $v->integralOrderDetail;
                    $newItem['content'] = $integralOrderDetail->goods_name;
                    $newItem['content_1'] = "积分兑换商品";
                    break;
                case 12:
                    /* @var IntegralOrderDetail $integralOrderDetail */
                    $integralOrderDetail = $v->integralOrderDetail;
                    $newItem['content'] = $integralOrderDetail->goods_name;
                    $newItem['content_1'] = "积分退还";
                    break;
                case 13:
                    /* @var MsOrder $order */
                    $order = $v->msOrder;
                    $newItem['content'] = "秒杀订单，订单号：" . $order->order_no;
                    $newItem['content_1'] = "积分退还";
                    break;
                default:
                    if ($v->integral >= 0) {
                        $newItem['content'] = "积分增加";
                        $newItem['content_1'] = "赠送积分";
                    } else {
                        $newItem['content'] = "积分减少";
                        $newItem['content_1'] = "扣除积分";
                    }
                    break;
            }
            $newItem['integral'] = $v->integral;
            $newList[] = $newItem;
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $newList,
            ],
        ];
    }

    private function getUrl($url)
    {
        preg_match('/^[^\?+]\?([\w|\W]+)=([\w|\W]*?)&([\w|\W]+)=([\w|\W]*?)$/', $url, $res);
        return $res;
    }

    public function getGoodsList()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = IntegralGoods::find()->where([
            'store_id' => $this->getCurrentStoreId(),
            'is_delete' => Model::IS_DELETE_FALSE,
            'status' => 1,
        ]);

        if ($this->cat_id) {
            $query->andWhere(['cat_id' => $this->cat_id]);
        }

        if ($this->cat_id == 0) {
            $query->andWhere(['is_index' => 1]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);

        $list = $query->orderBy('sort')->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();

        foreach ($list as &$item) {
            $num = 0;
            $attr_rows = json_decode($item['attr'], true);
            foreach ($attr_rows as $attr_row) {
                $num += intval($attr_row['num']);
            }
            $item['goods_num'] = $num;
        }
        unset($item);
        return [
            'code' => 0,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
            ]
        ];
    }
}
