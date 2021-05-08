<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/17
 * Time: 11:48
 */

namespace app\modules\api\models;

use app\models\Address;
use app\models\Attr;
use app\models\AttrGroup;
use app\models\Cart;
use app\models\common\api\CommonOrder;
use app\models\Coupon;
use app\models\Goods;
use app\models\Level;
use app\models\Mch;
use app\models\MiaoshaGoods;
use app\models\Model;
use app\models\Option;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderForm;
use app\models\PostageRules;
use app\models\FreeDeliveryRules;
use app\models\PrinterSetting;
use app\models\Store;
use app\models\User;
use app\models\UserCoupon;
use app\modules\api\controllers\OrderController;
use app\utils\PinterOrder;
use yii\helpers\VarDumper;
use app\models\TerritorialLimitation;
use app\models\Register;
use app\models\PondLog;
use app\models\ScratchLog;
use app\models\LotteryLog;
use app\models\StepUser;
use app\models\StepLog;

class OrderSubmitForm extends OrderData
{
    public $store_id;
    public $user_id;
    public $version;
    public $store;
    public $address;

    public $address_id;
    public $cart_id_list;
    public $cart_list;
    public $goods_info;

    public $user_coupon_id;

    public $content;
    public $offline;
    public $address_name;
    public $address_mobile;
    public $shop_id;

    public $use_integral;

    public $form;//自定义表单信息

    public $payment;
    public $mch_list;

    public $type;
    public $pond_id;
    public $scratch_id;
    public $lottery_id;
    public $step_id;
    public $mode;

    public function rules()
    {
        return [
            [['cart_id_list', 'goods_info', 'content', 'address_name', 'address_mobile', 'cart_list', 'mch_list'], 'string'],
            [['address_id',], 'required', 'on' => "EXPRESS"],
            [['address_name', 'address_mobile'], 'required', 'on' => "OFFLINE"],
            [['user_coupon_id', 'offline', 'shop_id', 'use_integral'], 'integer'],
            [['type'], 'default', 'value' => 0],
            [['pond_id', 'scratch_id', 'lottery_id', 'step_id'], 'default', 'value' => 0],
            [['offline'], 'default', 'value' => 0],
            [['payment'], 'default', 'value' => 0],
            [['form'], 'safe'],
            [['payment'], 'integer', 'message' => '请选择支付方式'],
            [['address_mobile'], 'match', 'pattern' => Model::MOBILE_PATTERN, 'message' => '手机号错误']
        ];
    }

    public function attributeLabels()
    {
        return [
            'address_id' => '收货地址',
            'address_name' => '收货人',
            'address_mobile' => '联系电话',
        ];
    }

    //兑奖 九宫格+刮刮卡+抽奖+布数宝
    public function convert()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $t = \Yii::$app->db->beginTransaction();

        //判断自提或者快递
        if ($this->offline != 1) {
            $address = Address::findOne([
                'id' => $this->address_id,
                'store_id' => $this->store_id,
                'user_id' => $this->user_id,
            ]);

            if (!$address) {
                return [
                    'code' => 1,
                    'msg' => '收货地址不存在',
                ];
            }
            $this->address = $address;

            $area = TerritorialLimitation::findOne([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'is_enable' => 1,
            ]);
            if ($area) {
                $city_id = [];  //限制的地区ID
                $detail = json_decode($area->detail);
                foreach ($detail as $key => $value) {
                    foreach ($value->province_list as $key2 => $value2) {
                        $city_id[] = $value2->id;
                    }
                }
                $addressArr = [
                    $address->province_id,
                    $address->city_id,
                    $address->district_id
                ];
                $addressArray = array_intersect($addressArr, $city_id);
                if (empty($addressArray)) {
                    return [
                        'code' => 1,
                        'msg' => '所选地区无货'
                    ];
                }
            }
        }

        $store = Store::findOne($this->store_id);
        $this->store = $store;

        $data = $this->getGoodsListByGoodsInfo($this->goods_info);
        $express_price = $data['express_price'];

        //包邮规则
        if ($express_price != 0) {
            $free = FreeDeliveryRules::find()->where(['store_id' => $this->store_id])->asArray()->all();
            foreach ($free as $k => $v) {
                $city = json_decode($v['city'], true);
                foreach ($city as $v1) {
                    if ($address->city == $v1['name'] && $data['total_price'] >= $v['price']) {
                        $express_price = 0;
                        break;
                    }
                }
            }
        }

        $order = new Order();

        if ($this->mode == 'scratch') {
            $gift = ScratchLog::find()
                ->where([
                    'store_id' => $this->store_id,
                    'status' => 1,
                    'id' => $this->scratch_id,
                    'user_id' => $this->user_id
                ])->with(['gift' => function ($query) {
                    $query->where([
                        'store_id' => $this->store_id,
                        'is_delete' => 0
                    ]);
                }])
                ->one();
        } elseif ($this->mode == 'pond') {
            $gift = PondLog::find()
                ->where([
                    'store_id' => $this->store_id,
                    'status' => 0,
                    'id' => $this->pond_id,
                    'user_id' => $this->user_id
                ])->with(['gift' => function ($query) {
                    $query->where([
                        'store_id' => $this->store_id,
                        'is_delete' => 0
                    ]);
                }])
                ->one();
        } elseif ($this->mode == 'lottery') {
            $gift = LotteryLog::find()
                ->where([
                    'store_id' => $this->store_id,
                    'id' => $this->lottery_id,
                    'user_id' => $this->user_id
                ])
                ->one();
            if (empty($gift)) {
                return [
                    'code' => 1,
                    'msg' => '已兑换或不存在',
                ];
            }

            if ($gift['status'] != 2) {
                $gift = LotteryLog::find()->where([
                            'store_id' => $this->store_id,
                            'lottery_id' => $gift->lottery_id,
                            'user_id' => $this->user_id,
                            'status' => 2
                        ])->with(['gift' => function ($query) {
                            $query->where([
                                'store_id' => $this->store_id,
                                'is_delete' => 0
                            ]);
                        }])->one();
            }
        } elseif ($this->mode == 'step') {
            $gift =  Goods::find()->where([
                    'store_id' => $this->store_id,
                    'id' => $this->step_id,
                    'is_delete' => 0,
                    'status' => 1,
                    'type' => 5,
                    'is_negotiable' => 0
                ])->one();
            $data = $this->getGoodsListByGoodsInfo($this->goods_info);
            if ($data['code'] == 1) {
                return $data;
            }
            $goods_list = empty($data['list']) ? [] : $data['list'];
            
            $total_price = empty($data['total_price']) ? 0 : $data['total_price'];
            $express_price = $data['express_price'];

            if (empty($goods_list)) {
                return [
                    'code' => 1,
                    'msg' => '订单提交失败，所选商品库存不足或已下架',
                ];
            }

            $goods = $goods_list[0];
            if (($goods->confine_count && $goods->confine_count > 0)) {
                $goodsNum = OrderDetail::find()->alias('od')->innerJoin(['o' => Order::tableName()], 'o.id=od.order_id')
                    ->where([
                        'od.goods_id' => $gift->id,'store_id' => $this->store_id,'o.user_id' => $this->user_id,
                        'o.is_cancel' => 0,'o.is_delete' => 0
                    ])->select('SUM(od.num) num')->scalar();
                if ($goodsNum) {
                } else {
                    $goodsNum = 0;
                }
                $goodsTotalNum = intval($goodsNum + $goods->num);
                if ($goodsTotalNum > $goods->confine_count) {
                    return [
                        'code' => 1,
                        'msg' => '商品：' . $gift->name . ' 超出购买数量',
                    ];
                }
            }

            $user = StepUser::findOne([
                    'store_id' => $this->store_id,
                    'user_id' => $this->user_id,
                    'is_delete' => 0
                ]);

            if ($total_price > $user['step_currency']) {
                return [
                    'code' => 1,
                    'msg' => '活力币不足',
                ];
            };
            $order->currency = $total_price;
        } else {
            return;
        }


        if (empty($gift)) {
            return [
                'code' => 1,
                'msg' => '已兑换或不存在',
            ];
        }
        if ($this->mode != 'step' && empty($gift->gift)) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已删除',
            ];
        }



        $order->store_id = $this->store_id;
        $order->user_id = $this->user_id;
        $order->order_no = $this->getOrderNo();


        $order->total_price = $express_price;
        $order->pay_price = $express_price;
        $order->express_price = $express_price;
        $order->discount = 10;
        $order->addtime = time();

        if ($this->offline == 0) {
            $order->address = $address->province . $address->city . $address->district . $address->detail;
            $order->mobile = $address->mobile;
            $order->name = $address->name;
            $order->address_data = json_encode([
                'province' => $address->province,
                'city' => $address->city,
                'district' => $address->district,
                'detail' => $address->detail,
            ], JSON_UNESCAPED_UNICODE);
        } else {
            $order->name = $this->address_name;
            $order->mobile = $this->address_mobile;
            $order->shop_id = $this->shop_id;
        }
        $order->first_price = 0;
        $order->second_price = 0;
        $order->third_price = 0;
        $order->content = $this->content;
        $order->is_offline = $this->offline;
        $order->version = $this->version;

        if ($express_price) {
            if ($this->payment == 2) {
                $order->pay_type = 2;
                $order->is_pay = 0;
            }
            if ($this->payment == 3) {
                $order->pay_type = 3;
                $order->is_pay = 0;
            }
        } else {
            $order->is_pay = 1;
            $order->pay_type = 0;
        }

        if ($this->mode == 'scratch') {
            $order->type = 3;
        } elseif ($this->mode == 'pond') {
            $order->type = 1;
        } elseif ($this->mode == 'lottery') {
            $order->type = 4;
        } elseif ($this->mode == 'step') {
            $order->type = 5;
            $attr_id_list = [];
            foreach ($goods->attr_list as $item) {
                array_push($attr_id_list, $item['attr_id']);
            }
            $_goods = Goods::findOne($goods->goods_id);

            if (!$_goods->numSub($attr_id_list, $goods->num)) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '订单提交失败，商品“' . $_goods->name . '”库存不足',
                    'attr_id_list' => $attr_id_list,
                    'attr_list' => $gift->attr,
                ];
            };
        }

        $order->coupon_sub_price = 0;
        $order->mch_id = 0;
        $order->address_data = json_encode([
            'province' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'detail' => $address->detail,
        ], JSON_UNESCAPED_UNICODE);


        if ($order->save()) {
            if ($this->mode == 'step') {
                $step_log = new StepLog();
                $step_log->store_id = $this->store_id;
                $step_log->step_id = $user['id'];
                $step_log->num = 0;
                $step_log->status = 2;
                $step_log->type = 1;
                $step_log->step_currency = $total_price;
                $step_log->type_id = $order->id;
                $step_log->raffle_time = time();
                $step_log->create_time = time();
                $step_log->save();

                $user->step_currency = $user['step_currency'] - $total_price;
                if (!$user->save()) {
                    $t->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '兑换失败，请稍后再重试',
                    ];
                }


                $order_detail = new OrderDetail();
                $order_detail->order_id = $order->id;
                $order_detail->goods_id = $goods->goods_id;
                $order_detail->num = $goods->num;
                $order_detail->total_price = 0;
                $order_detail->addtime = time();
                $order_detail->is_delete = 0;
                $order_detail->attr = json_encode($goods->attr_list, JSON_UNESCAPED_UNICODE);
                $order_detail->pic = $goods->goods_pic;
                $order_detail->integral = $goods->give;
            } else {
                if ($this->mode == 'scratch') {
                    $gift->status = 2;
                } elseif ($this->mode == 'pond') {
                    $gift->status = 1;
                } elseif ($this->mode == 'lottery') {
                    $gift->status = 3;
                }
                $gift->raffle_time = time();
                $gift->order_id = $order->id;
                if (!$gift->save()) {
                    $t->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '兑换失败，请稍后再重试',
                    ];
                }
                $goods = $gift->gift;

                $order_detail = new OrderDetail();
                $order_detail->order_id = $order->id;
                $order_detail->goods_id = $goods->id;
                $order_detail->num = 1;
                $order_detail->total_price = 0;
                $order_detail->addtime = time();
                $order_detail->is_delete = 0;
                $order_detail->attr = $gift->attr;
                $order_detail->pic = $goods->cover_pic;
                $order_detail->integral = 0;
                $attr_id_list = [];

                foreach (json_decode($gift->attr, true) as $item) {
                    array_push($attr_id_list, $item['attr_id']);
                }
                $_goods = Goods::findOne($goods->id);
            }
            



            /*if (!$_goods->numSub($attr_id_list, $order_detail->num)) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '订单提交失败，商品“' . $_goods->name . '”库存不足',
                    'attr_id_list' => $attr_id_list,
                    'attr_list' => $gift->attr,
                ];
            } */
            if (!$order_detail->save()) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '订单提交失败，请稍后再重试',
                ];
            }
            $order_id = $order->id;
        }


        $t->commit();
        if ($order_id) {
            return [
                'code' => 0,
                'msg' => '提交成功',
                'data' => (object)[
                    'order_id' => $order_id,
                    'p_price' => $express_price
                ],
            ];
        } else {
            $t->rollBack();
            return $this->getErrorResponse($order);
        }
    }


    //九宫格 可删。。兼容旧版本
    public function pond()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $t = \Yii::$app->db->beginTransaction();

        //判断自提或者快递
        if ($this->offline == 1) {
        } else {
            $address = Address::findOne([
                'id' => $this->address_id,
                'store_id' => $this->store_id,
                'user_id' => $this->user_id,
            ]);
            if (!$address) {
                return [
                    'code' => 1,
                    'msg' => '收货地址不存在',
                ];
            }
            $this->address = $address;

            $area = TerritorialLimitation::findOne([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'is_enable' => 1,
            ]);
            if ($area) {
                $city_id = [];  //限制的地区ID
                $detail = json_decode($area->detail);
                foreach ($detail as $key => $value) {
                    foreach ($value->province_list as $key2 => $value2) {
                        $city_id[] = $value2->id;
                    }
                }
                if (!in_array($address->city_id, $city_id)) {
                    return [
                        'code' => 1,
                        'msg' => '所选地区无货'
                    ];
                }
            }
        }

        $store = Store::findOne($this->store_id);
        $this->store = $store;

        $data = $this->getGoodsListByGoodsInfo($this->goods_info);
        $express_price = $data['express_price'];

        //包邮规则
        if ($express_price != 0) {
            $free = FreeDeliveryRules::find()->where(['store_id' => $this->store_id])->asArray()->all();
            foreach ($free as $k => $v) {
                $city = json_decode($v['city'], true);
                foreach ($city as $v1) {
                    if ($address->city == $v1['name'] && $data['total_price'] >= $v['price']) {
                        $express_price = 0;
                        break;
                    }
                }
            }
        }

        $pond_gift = PondLog::find()
            ->where([
                'store_id' => $this->store_id,
                'status' => 0,
                'id' => $this->pond_id,
                'user_id' => $this->user_id
            ])->with(['gift' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0
                ]);
            }])
            ->one();
        if (empty($pond_gift)) {
            return [
                'code' => 1,
                'msg' => '已兑换或不存在',
            ];
        }
        if (empty($pond_gift->gift)) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已删除',
            ];
        }


        $order = new Order();
        $order->store_id = $this->store_id;
        $order->user_id = $this->user_id;
        $order->order_no = $this->getOrderNo();
        $order->pay_price = $express_price;
        $order->total_price = $express_price;
        $order->express_price = $express_price;
        $order->version = $this->version;
        $order->first_price = 0;
        $order->second_price = 0;
        $order->third_price = 0;
        $order->type = 1;
        $order->content = $this->content;
        $order->discount = 10;
        $order->coupon_sub_price = 0;
        $order->address = $address->province . $address->city . $address->district . $address->detail;
        $order->mobile = $address->mobile;
        $order->name = $address->name;
        $order->addtime = time();
        $order->mch_id = 0;
        $order->address_data = json_encode([
            'province' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'detail' => $address->detail,
        ], JSON_UNESCAPED_UNICODE);
        if ($express_price) {
            if ($this->payment == 2) {
                $order->pay_type = 2;
                $order->is_pay = 0;
            }
            if ($this->payment == 3) {
                $order->pay_type = 3;
                $order->is_pay = 0;
            }
        } else {
            $order->is_pay = 1;
            $order->pay_type = 0;
        }

        if ($order->save()) {
            $pond_gift->status = 1;
            $pond_gift->raffle_time = time();
            $pond_gift->order_id = $order->id;
            if (!$pond_gift->save()) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '兑换失败，请稍后再重试',
                ];
            }
            $goods = $pond_gift->gift;

            $order_detail = new OrderDetail();
            $order_detail->order_id = $order->id;
            $order_detail->goods_id = $goods->id;
            $order_detail->num = 1;
            $order_detail->total_price = 0;
            $order_detail->addtime = time();
            $order_detail->is_delete = 0;
            $order_detail->attr = $pond_gift->attr;
            $order_detail->pic = $goods->cover_pic;
            $order_detail->integral = 0;
            $attr_id_list = [];

            foreach (json_decode($pond_gift->attr, true) as $item) {
                array_push($attr_id_list, $item['attr_id']);
            }
            $_goods = Goods::findOne($goods->id);

            if (!$_goods->numSub($attr_id_list, $order_detail->num)) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '订单提交失败，商品“' . $_goods->name . '”库存不足',
                    'attr_id_list' => $attr_id_list,
                    'attr_list' => $pond_gift->attr,
                ];
            }
            if (!$order_detail->save()) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '订单提交失败，请稍后再重试',
                ];
            }
            $order_id = $order->id;
        }


        $t->commit();
        if ($order_id) {
            return [
                'code' => 0,
                'msg' => '提交成功',
                'data' => (object)[
                    'order_id' => $order_id,
                    'p_price' => $express_price
                ],
            ];
        } else {
            $t->rollBack();
            return $this->getErrorResponse($order);
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $t = \Yii::$app->db->beginTransaction();
        $mch_list = json_decode($this->mch_list, true);

        //判断自定义表单是否按照要求填写
        $form_list = $this->getForm();
        //判断自提或者快递
        if ($this->offline == 1) {
        } else {
            $address = Address::findOne([
                'id' => $this->address_id,
                'store_id' => $this->store_id,
                'user_id' => $this->user_id,
            ]);
            if (!$address) {
                return [
                    'code' => 1,
                    'msg' => '收货地址不存在',
                ];
            }
            $this->address = $address;

            $area = TerritorialLimitation::findOne([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'is_enable' => 1,
            ]);
            if ($area) {
                $city_id = [];  //限制的地区ID
                $detail = json_decode($area->detail);
                foreach ($detail as $key => $value) {
                    foreach ($value->province_list as $key2 => $value2) {
                        $city_id[] = $value2->id;
                    }
                }
                $addressArr = [
                    $address->province_id,
                    $address->city_id,
                    $address->district_id
                ];
                $addressArray = array_intersect($addressArr, $city_id);
                if (empty($addressArray)) {
                    return [
                        'code' => 1,
                        'msg' => '所选地区无货'
                    ];
                }
            }
        }
        if (!in_array($this->payment, [0, 2, 3])) {
            return [
                'code' => 1,
                'msg' => '请选择支付方式'
            ];
        }

        $store = Store::findOne($this->store_id);
        $this->store = $store;
        if ($this->cart_id_list || $this->mch_list) {//购物车订单（新增多商户的商品）
            $data = $this->getGoodsListByCartIdList($this->cart_id_list);
            if (is_array($mch_list)) {
                $data['mch_list'] = [];
                foreach ($mch_list as $mch) {
                    $mch_data = $this->getGoodsListByCartIdList(json_encode($mch['cart_id_list']));
                    $mch_data['mch_id'] = $mch['id'];
                    $mch_data['content'] = $mch['content'];
                    $data['mch_list'][] = $mch_data;
                }
            }
        } elseif ($this->goods_info) {//直接购买订单
            $data = $this->getGoodsListByGoodsInfo($this->goods_info);


            if ($data['list'][0]->mch_id) {
                $tmp_data = $data;
                if (!is_array($data['mch_list'])) {
                    $data['mch_list'] = [];
                }
                $mch = Mch::findOne($data['list'][0]->mch_id);
                $tmp_data['mch_id'] = $mch->id;
                $tmp_data['content'] = $this->content;
                $data = [
                    'total_price' => 0,
                    'express_price' => 0,
                    'list' => [],
                    'resIntegral' => [
                        'forehead' => 0,
                        'forehead_integral' => 0,
                    ],
                    'mch_list' => [
                        $tmp_data,
                    ],
                ];
            }
        } elseif ($this->cart_list) {//快速购买订单（新增多商户的商品）
            $data = $this->getGoodsListByQuickCartIdList($this->cart_list);
        }
        if ($data['code'] == 1) {
            return $data;
        }
        $goods_list = empty($data['list']) ? [] : $data['list'];
        $mch_list = empty($data['mch_list']) ? [] : $data['mch_list'];
        $total_price = empty($data['total_price']) ? 0 : $data['total_price'];
        if ($this->use_integral == 2) {//不是用积分抵扣
            $resIntegral = [
                'forehead' => 0,
                'forehead_integral' => 0,
            ];
        } else {
            $resIntegral = $data['resIntegral'];
        }
        $express_price = $data['express_price'];
        if (empty($goods_list) && empty($mch_list)) {
            return [
                'code' => 1,
                'msg' => '订单提交失败，所选商品库存不足或已下架',
            ];
        }
        $order_id_list = [];
        if (!empty($goods_list)) {//平台自营下的单子
            $total_price_1 = $total_price + $express_price;
            // 获取用户当前积分
            $user = User::findOne(['id' => $this->user_id, 'type' => 1, 'is_delete' => 0]);
            if ($user->integral < $resIntegral['forehead_integral']) {
                $resIntegral['forehead_integral'] = $user->integral;
                $resIntegral['forehead'] = sprintf("%.2f", $user->integral / $store->integral);
            }

            $order = new Order();
            $order->store_id = $this->store_id;
            $order->user_id = $this->user_id;
            $order->order_no = $this->getOrderNo();

            // 此处判断起送规则
            $this->total_price = $total_price;
            $checkOffer = $this->checkOfferRule();
            if ($checkOffer['code'] == 1) {
                return $checkOffer;
            }


            // 此处计算所有的优惠措施

            $total_price_2 = $total_price; //实际支付金额
            // 减去 优惠券（不含运费）
            if ($this->user_coupon_id) {
                $coupon = Coupon::find()->alias('c')
                    ->leftJoin(['uc' => UserCoupon::tableName()], 'uc.coupon_id=c.id')
                    ->where([
                        'uc.id' => $this->user_coupon_id,
                        'uc.is_delete' => 0,
                        'uc.is_use' => 0,
                        'uc.is_expire' => 0,
                        'uc.user_id' => $this->user_id
                    ])
                    ->select('c.*')->one();
                $goods_total_pay_price = $total_price_2;//原本需支付的商品总价
                if ($coupon && $goods_total_pay_price >= $coupon->min_price) {
                    $goods_price = $goods_total_pay_price - $coupon->sub_price;
                    $total_price_2 = max(0.01, round($goods_price, 2));
                    $order->coupon_sub_price = $goods_total_pay_price - max(0.01, $goods_total_pay_price - $coupon->sub_price);
                    UserCoupon::updateAll(['is_use' => 1], ['id' => $this->user_coupon_id]);
                    $order->user_coupon_id = $this->user_coupon_id;
                }
            }
            // 减去 积分折扣金额 （不含运费）
            $total_price_2 = $total_price_2 - $resIntegral['forehead'];
            //减去折扣（不含运费）
            $level = Level::find()->where(['store_id' => $this->store_id, 'level' => \Yii::$app->user->identity->level])->asArray()->one();
            if ($level) {
                $discount = $level['discount'];
            } else {
                $discount = 10;
            }
            $total_price_2 = max(0.01, round($total_price_2 * $discount / 10, 2));
            //包邮规则
            if ($express_price != 0) {
                $free = FreeDeliveryRules::find()->where(['store_id' => $this->store_id])->asArray()->all();
                foreach ($free as $k => $v) {
                    $city = json_decode($v['city'], true);
                    foreach ($city as $v1) {
                        if ($address->city == $v1['name'] && $total_price >= $v['price']) {
                            $order->express_price_1 = $express_price;
                            $express_price = 0;
                            break;
                        }
                    }
                }
            }
            $total_price_3 = $total_price_2 + $express_price;//实际支付金额加上运费


            $order->total_price = $total_price_1;
            $order->pay_price = $total_price_3 < 0.01 ? 0.01 : $total_price_3;
            $order->express_price = $express_price;
            $order->discount = $discount;
            $order->addtime = time();
            if ($this->offline == 0) {
                $order->address = $address->province . $address->city . $address->district . $address->detail;
                $order->mobile = $address->mobile;
                $order->name = $address->name;
                $order->address_data = json_encode([
                    'province' => $address->province,
                    'city' => $address->city,
                    'district' => $address->district,
                    'detail' => $address->detail,
                ], JSON_UNESCAPED_UNICODE);
            } else {
                $order->name = $this->address_name;
                $order->mobile = $this->address_mobile;
                $order->shop_id = $this->shop_id;
            }
            $order->first_price = 0;
            $order->second_price = 0;
            $order->third_price = 0;
            $order->content = $this->content;
            $order->is_offline = $this->offline;
            $order->integral = json_encode($resIntegral, JSON_UNESCAPED_UNICODE);
            $order->version = $this->version;
            if ($this->payment == 2) {
                $order->pay_type = 2;
                $order->is_pay = 0;
            }
            if ($this->payment == 3) {
                $order->pay_type = 3;
                $order->is_pay = 0;
            }
            if ($order->save()) {
                foreach ($form_list as $index => $value) {
                    $order_form = new OrderForm();
                    $order_form->store_id = $this->store_id;
                    $order_form->order_id = $order->id;
                    $order_form->key = $value['name'];
                    $order_form->value = $value['default'];
                    $order_form->type = $value['type'];
                    $order_form->is_delete = 0;
                    $order_form->save();
                }

                // 减去当前用户账户积分
                if ($resIntegral['forehead_integral'] > 0) {
                    $user->integral -= $resIntegral['forehead_integral'];
                    $register = new Register();
                    $register->store_id = $this->store->id;
                    $register->user_id = $user->id;
                    $register->register_time = '..';
                    $register->addtime = time();
                    $register->continuation = 0;
                    $register->type = 4;
                    $register->integral = '-' . $resIntegral['forehead_integral'];
                    $register->order_id = $order->id;
                    $register->save();
                    $user->save();
                }
                $goods_total_pay_price = $order->pay_price - $order->express_price;
                $goods_total_price = 0.00;
                foreach ($goods_list as $goods) {
                    $goods_total_price += $goods->price;
                }
                foreach ($goods_list as $goods) {
                    $order_detail = new OrderDetail();
                    $order_detail->order_id = $order->id;
                    $order_detail->goods_id = $goods->goods_id;
                    $order_detail->num = $goods->num;
                    $order_detail->total_price = doubleval(sprintf('%.2f', $goods_total_pay_price * $goods->price / $goods_total_price));
                    $order_detail->addtime = time();
                    $order_detail->is_delete = 0;
                    $order_detail->attr = json_encode($goods->attr_list, JSON_UNESCAPED_UNICODE);
                    $order_detail->pic = $goods->goods_pic;
                    $order_detail->integral = $goods->give;

                    $attr_id_list = [];
                    foreach ($goods->attr_list as $item) {
                        array_push($attr_id_list, $item['attr_id']);
                    }
                    $_goods = Goods::findOne($goods->goods_id);
                    if (!$_goods->numSub($attr_id_list, $order_detail->num)) {
                        $t->rollBack();
                        return [
                            'code' => 1,
                            'msg' => '订单提交失败，商品“' . $_goods->name . '”库存不足',
                            'attr_id_list' => $attr_id_list,
                            'attr_list' => $goods->attr_list,
                        ];
                    }

                    if (!$order_detail->save()) {
                        $t->rollBack();
                        return [
                            'code' => 1,
                            'msg' => '订单提交失败，请稍后再重试',
                        ];
                    }
                }
                $printer_order = new PinterOrder($this->store_id, $order->id, 'order', 0);
                $res = $printer_order->print_order();
                $order_id_list[] = $order->id;
            } else {
                $t->rollBack();
                return $this->getErrorResponse($order);
            }
        }
        if (!empty($data['mch_list'])) {//入驻商商品下的单子
            foreach ($data['mch_list'] as $mch) {
                $order = new Order();
                $order->store_id = $this->store_id;
                $order->user_id = $this->user_id;
                $order->order_no = $this->getOrderNo();
                $order->total_price = $mch['total_price'] + $mch['express_price'];
                $order->pay_price = $order->total_price;
                $order->express_price = $mch['express_price'];
                $order->version = $this->version;
                $order->first_price = 0;
                $order->second_price = 0;
                $order->third_price = 0;
                $order->content = $mch['content'];
                $order->discount = 10;
                $order->coupon_sub_price = 0;
                $order->address = $address->province . $address->city . $address->district . $address->detail;
                $order->mobile = $address->mobile;
                $order->name = $address->name;
                $order->addtime = time();
                $order->mch_id = $mch['mch_id'];
                $order->address_data = json_encode([
                    'province' => $address->province,
                    'city' => $address->city,
                    'district' => $address->district,
                    'detail' => $address->detail,
                ], JSON_UNESCAPED_UNICODE);
                if ($this->payment == 2) {
                    $order->pay_type = 2;
                    $order->is_pay = 0;
                }
                if ($this->payment == 3) {
                    $order->pay_type = 3;
                    $order->is_pay = 0;
                }
                if ($order->save()) {
                    $goods_list = $mch['list'];
                    foreach ($goods_list as $goods) {
                        $order_detail = new OrderDetail();
                        $order_detail->order_id = $order->id;
                        $order_detail->goods_id = $goods->goods_id;
                        $order_detail->num = $goods->num;
                        $order_detail->total_price = $goods->price;
                        $order_detail->addtime = time();
                        $order_detail->is_delete = 0;
                        $order_detail->attr = json_encode($goods->attr_list, JSON_UNESCAPED_UNICODE);
                        $order_detail->pic = $goods->goods_pic;
                        $order_detail->integral = 0;
                        $attr_id_list = [];
                        foreach ($goods->attr_list as $item) {
                            array_push($attr_id_list, $item['attr_id']);
                        }
                        $_goods = Goods::findOne($goods->goods_id);
                        if (!$_goods->numSub($attr_id_list, $order_detail->num)) {
                            $t->rollBack();
                            return [
                                'code' => 1,
                                'msg' => '订单提交失败，商品“' . $_goods->name . '”库存不足',
                                'attr_id_list' => $attr_id_list,
                                'attr_list' => $goods->attr_list,
                            ];
                        }
                        if (!$order_detail->save()) {
                            $t->rollBack();
                            return [
                                'code' => 1,
                                'msg' => '订单提交失败，请稍后再重试',
                            ];
                        }
                    }
                }
                $order_id_list[] = $order->id;
            }
        }
        $t->commit();
        if (count($order_id_list) > 0) {
            if (count($order_id_list) > 1) {//多个订单合并
                return [
                    'code' => 0,
                    'msg' => '订单提交成功',
                    'data' => (object)[
                        'order_id_list' => $order_id_list,
                    ],
                ];
            } else {//单个订单
                return [
                    'code' => 0,
                    'msg' => '订单提交成功',
                    'data' => (object)[
                        'order_id' => $order_id_list[0],
                    ],
                ];
            }
        } else {
            $t->rollBack();
            return $this->getErrorResponse($order);
        }
    }


    /**
     * 获取购物车商品列表及总价
     * @param string $cart_id_list
     * eg.[1,2,3]
     * @return array 'list'=>商品列表,'total_price'=>总价
     */
    private function getGoodsListByCartIdList($cart_id_list)
    {
        /* @var  Cart[] $cart_list */
        $cart_list = Cart::find()->where([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_delete' => 0,
            'id' => json_decode($cart_id_list, true),
        ])->all();
        $list = [];
        $total_price = 0;
        $new_cart_id_list = [];
        $resIntegral = [
            'forehead' => 0,
            'forehead_integral' => 0,
        ];
        $goodsIds = [];
        $goodsList = [];
        foreach ($cart_list as $item) {
            if ($item->num < 1) {
                continue;
            }
            $goods = Goods::findOne([
                'store_id' => $this->store_id,
                'id' => $item->goods_id,
                'is_delete' => 0,
                'status' => 1,
            ]);
            if (!$goods) {
                continue;
            }
            $attr_list = Attr::find()->alias('a')
                ->select('a.id attr_id,ag.attr_group_name,a.attr_name')
                ->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
                ->where(['a.id' => json_decode($item->attr, true)])
                ->asArray()->all();
            $goods_attr_info = $goods->getAttrInfo(json_decode($item->attr, true));
            $attr_num = intval(empty($goods_attr_info['num']) ? 0 : $goods_attr_info['num']);
            if ($attr_num < $item->num) {
                continue;
            }

            foreach ($attr_list as &$i) {
                $i['no'] = isset($goods_attr_info['no']) ? $goods_attr_info['no'] : 0;
            }
            unset($i);

            $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->getGoodsPic(0)->pic_url : $goods->getGoodsPic(0)->pic_url;
            $new_item = (object)[
                'cart_id' => $item->id,
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'goods_pic' => $goods_pic,
                'num' => $item->num,
                'price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']) * $item->num,
                'attr_list' => $attr_list,
                'max_num' => $attr_num,
                'give' => 0,

                'single_price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']),
                'freight' => $goods->freight,
                'integral' => $goods->integral,
                'weight' => $goods->weight,
                'full_cut' => $goods->full_cut,
                'mch_id' => $goods->mch_id,
            ];
            $new_goods = [
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'freight' => $goods->freight,
                'weight' => $goods->weight,
                'num' => $new_item->num,
                'full_cut' => $goods->full_cut,
                'price' => $new_item->price,
                'mch_id' => $goods->mch_id,
            ];
            $goodsList[] = $new_goods;
            //积分计算
            $integral_arr = OrderData::integral($new_item, $this->store->integral, $goodsIds);
            $resIntegral['forehead_integral'] += $integral_arr['resIntegral']['forehead_integral'];
            $resIntegral['forehead'] += $integral_arr['resIntegral']['forehead'];
            $new_item->give = $integral_arr['give'];
            $goodsIds[] = $goods->id;


            $total_price += $new_item->price;
            $new_cart_id_list[] = $item->id;
            $list[] = $new_item;
            $item->is_delete = 1;
            $item->save();
        }
        $express_price = 0;
        if ($this->offline == 0) {
            $address = $this->address;
            $resGoodsList = Goods::cutFull($goodsList);
            $express_price = PostageRules::getExpressPriceMore($this->store_id, $address->city_id, $resGoodsList, $address->province_id);
        }
        return [
            'total_price' => $total_price,
            'cart_id_list' => $new_cart_id_list,
            'list' => $list,
            'resIntegral' => $resIntegral,
            'express_price' => $express_price
        ];
    }


    private function getGoodsListByQuickCartIdList($cart_list)
    {
        $cart_list = json_decode($cart_list);
        $list = [];
        $total_price = 0;
        $resIntegral = [
            'forehead' => 0,
            'forehead_integral' => 0,
        ];
        $goodsIds = [];
        $goodsList = [];
        foreach ($cart_list as $item) {
            if (!is_int($item->num) || $item->num < 1) {
                continue;
            }
            $goods = Goods::findOne([
                'store_id' => $this->store_id,
                'id' => $item->id,
                'is_delete' => 0,
                'status' => 1,
            ]);
            if (!$goods) {
                continue;
            }
            $attr_id_list = [];
            foreach ($item->attr as $key => $value) {
                array_push($attr_id_list, $value->attr_id);
            }
            $attr_list = Attr::find()->alias('a')
                ->select('a.id attr_id,ag.attr_group_name,a.attr_name')
                ->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
                ->where(['a.id' => $attr_id_list])
                ->asArray()->all();

            $goods_attr_info = $goods->getAttrInfo($attr_id_list);
            $attr_num = intval(empty($goods_attr_info['num']) ? 0 : $goods_attr_info['num']);
            if ($attr_num < $item->num) {
                continue;
            }

            foreach ($attr_list as &$i) {
                $i['no'] = isset($goods_attr_info['no']) ? $goods_attr_info['no'] : 0;
            }
            unset($i);
            $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->getGoodsPic(0)->pic_url : $goods->getGoodsPic(0)->pic_url;
            $new_item = (object)[
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'goods_pic' => $goods_pic,
                'num' => $item->num,
                'price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']) * $item->num,
                'attr_list' => $attr_list,
                'max_num' => $attr_num,
                'give' => 0,

                'single_price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']),
                'freight' => $goods->freight,
                'integral' => $goods->integral,
                'weight' => $goods->weight,
                'full_cut' => $goods->full_cut,
            ];
            $new_goods = [
                'goods_id' => $goods->id,
                'goods_name' => $goods->name,
                'freight' => $goods->freight,
                'weight' => $goods->weight,
                'num' => $new_item->num,
                'full_cut' => $goods->full_cut,
                'price' => $new_item->price,
            ];
            $goodsList[] = $new_goods;
            //积分计算
            $integral_arr = OrderData::integral($new_item, $this->store->integral, $goodsIds);
            $resIntegral['forehead_integral'] += $integral_arr['resIntegral']['forehead_integral'];
            $resIntegral['forehead'] += $integral_arr['resIntegral']['forehead'];
            $new_item->give = $integral_arr['give'];
            $goodsIds[] = $goods->id;


            $total_price += $new_item->price;
            $list[] = $new_item;
        }
        $express_price = 0;
        if ($this->offline == 0) {
            $resGoodsList = Goods::cutFull($goodsList);
            $express_price = PostageRules::getExpressPriceMore($this->store_id, $this->address->city_id, $resGoodsList, $this->address->province_id);
        }

        return [
            'total_price' => $total_price,
            'list' => $list,
            'resIntegral' => $resIntegral,
            'express_price' => $express_price
        ];
    }

    /**
     * @param string $goods_info
     * eg.{"goods_id":"22","attr":[{"attr_group_id":1,"attr_group_name":"颜色","attr_id":3,"attr_name":"橙色"},{"attr_group_id":2,"attr_group_name":"尺码","attr_id":2,"attr_name":"M"}],"num":1}
     */
    private function getGoodsListByGoodsInfo($goods_info)
    {
        $goods_info = json_decode($goods_info);

        $goods = Goods::findOne([
            'id' => $goods_info->goods_id,
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'status' => 1,
        ]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已下架'
            ];
        }
        if (!is_int($goods_info->num) || $goods_info->num < 1) {
            return [
                'code' => 1,
                'msg' => '商品数量不正确',
            ];
        }
        $attr_id_list = [];
        foreach ($goods_info->attr as $item) {
            array_push($attr_id_list, $item->attr_id);
        }

        $total_price = 0;
        $goods_attr_info = $goods->getAttrInfo($attr_id_list);

        $attr_list = Attr::find()->alias('a')
            ->select('a.id attr_id,ag.attr_group_name,a.attr_name')
            ->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
            ->where(['a.id' => $attr_id_list])
            ->asArray()->all();

        foreach ($attr_list as &$i) {
            $i['no'] = isset($goods_attr_info['no']) ? $goods_attr_info['no'] : 0;
        }
        unset($i);

        $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->getGoodsPic(0)->pic_url : $goods->getGoodsPic(0)->pic_url;
        $goods_item = (object)[
            'goods_id' => $goods->id,
            'goods_name' => $goods->name,
            'goods_pic' => $goods_pic,
            'num' => $goods_info->num,
            'price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']) * $goods_info->num,
            'attr_list' => $attr_list,
            'give' => 0,
            'confine_count' => $goods->confine_count,

            'single_price' => doubleval(empty($goods_attr_info['price']) ? $goods->price : $goods_attr_info['price']),
            'freight' => $goods->freight,
            'integral' => $goods->integral,
            'weight' => $goods->weight,
            'full_cut' => $goods->full_cut,
            'mch_id' => $goods->mch_id,
        ];

        //积分计算
        $integral_arr = OrderData::integral($goods_item, $this->store->integral);

        $resIntegral = $integral_arr['resIntegral'];
        $goods_item->give = $integral_arr['give'];

        //运费计算
        $express_price = 0;
        if ($this->offline == 0) {
            if ($goods['full_cut']) {
                $full_cut = json_decode($goods['full_cut'], true);
            } else {
                $full_cut = [
                    'pieces' => 0,
                    'forehead' => 0,
                ];
            }
            if ((empty($full_cut['pieces']) || $goods_item->num < ($full_cut['pieces'] ?: 0)) && (empty($full_cut['forehead']) || $goods_item->price < ($full_cut['forehead'] ?: 0))) {
                $express_price = PostageRules::getExpressPrice($this->store_id, $this->address->city_id, $goods, $goods_item->num, $this->address->province_id);
            }
        }

        $total_price += $goods_item->price;
        return [
            'total_price' => $total_price,
            'list' => [$goods_item],
            'resIntegral' => $resIntegral,
            'express_price' => $express_price
        ];
    }

    public function getOrderNo()
    {
        $store_id = empty($this->store_id) ? 0 : $this->store_id;
        $order_no = null;
        while (true) {
            $order_no = date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_no = Order::find()->where(['order_no' => $order_no])->exists();
            if (!$exist_order_no) {
                break;
            }
        }
        return $order_no;
    }


    /**
     * @param Goods $goods
     * @param array $attr_id_list eg.[12,34,22]
     * @return array ['id'=>'miaosha_goods id','attr_list'=>[],'miaosha_price'=>'秒杀价格','miaosha_num'=>'秒杀数量','sell_num'=>'已秒杀商品数量']
     */
    private function getMiaoshaData($goods, $attr_id_list = [])
    {
        $miaosha_goods = MiaoshaGoods::findOne([
            'goods_id' => $goods->id,
            'is_delete' => 0,
            'open_date' => date('Y-m-d'),
            'start_time' => intval(date('H')),
        ]);
        if (!$miaosha_goods) {
            return null;
        }
        $attr_data = json_decode($miaosha_goods->attr, true);
        sort($attr_id_list);
        $miaosha_data = null;
        foreach ($attr_data as $i => $attr_data_item) {
            $_tmp_attr_id_list = [];
            foreach ($attr_data_item['attr_list'] as $item) {
                $_tmp_attr_id_list[] = $item['attr_id'];
            }
            sort($_tmp_attr_id_list);
            if ($attr_id_list == $_tmp_attr_id_list) {
                $miaosha_data = $attr_data_item;
                break;
            }
        }
        $miaosha_data['id'] = $miaosha_goods->id;
        return $miaosha_data;
    }

    /**
     * 获取商品秒杀价格，若库存不足则使用商品原价，若有部分库存，则部分数量使用秒杀价，部分使用商品原价，商品库存不足返回false
     * @param array $miaosha_data ['attr_list'=>[],'miaosha_price'=>'秒杀价格','miaosha_num'=>'秒杀数量','sell_num'=>'已秒杀商品数量']
     * @param Goods $goods
     * @param array $attr_id_list eg.[12,34,22]
     * @param integer $buy_num 购买数量
     *
     * @return false|array
     */
    private function getMiaoshaPrice($miaosha_data, $goods, $attr_id_list, $buy_num)
    {
        $attr_data = json_decode($goods->attr, true);
        sort($attr_id_list);
        $goost_attr_data = null;
        foreach ($attr_data as $i => $attr_data_item) {
            $_tmp_attr_id_list = [];
            foreach ($attr_data_item['attr_list'] as $item) {
                $_tmp_attr_id_list[] = intval($item['attr_id']);
            }
            sort($_tmp_attr_id_list);
            if ($attr_id_list == $_tmp_attr_id_list) {
                $goost_attr_data = $attr_data_item;
                break;
            }
        }
        $goods_price = $goost_attr_data['price'];
        if (!$goods_price) {
            $goods_price = $goods->price;
        }

        $miaosha_price = min($miaosha_data['miaosha_price'], $goods_price);

        if ($buy_num > $goost_attr_data['num']) {//商品库存不足
            \Yii::warning([
                'res' => '库存不足',
                'm_data' => $miaosha_data,
                'g_data' => $goost_attr_data,
                '$attr_id_list' => $attr_id_list,
            ]);
            return false;
        }

        if ($buy_num <= ($miaosha_data['miaosha_num'] - $miaosha_data['sell_num'])) {
            \Yii::warning([
                'res' => '库存充足',
                'price' => $buy_num * $miaosha_price,
                'm_data' => $miaosha_data,
            ]);
            return [
                'miaosha_price_num' => $buy_num,
                'original_price_num' => 0,
                'total_price' => $buy_num * $miaosha_price
            ];
        }

        $miaosha_num = ($miaosha_data['miaosha_num'] - $miaosha_data['sell_num']);
        $original_num = $buy_num - $miaosha_num;

        \Yii::warning([
            'res' => '部分充足',
            'price' => $miaosha_num * $miaosha_price + $original_num * $goods_price,
            'm_data' => $miaosha_data,
        ]);

        return [
            'miaosha_price_num' => $miaosha_num,
            'original_price_num' => $original_num,
            'total_price' => $miaosha_num * $miaosha_price + $original_num * $goods_price,
        ];
    }

    private function setMiaoshaSellNum($miaosha_goods_id, $attr_id_list, $num)
    {
        $miaosha_goods = MiaoshaGoods::findOne($miaosha_goods_id);
        if (!$miaosha_goods) {
            return false;
        }
        sort($attr_id_list);
        $attr_data = json_decode($miaosha_goods->attr, true);
        foreach ($attr_data as $i => $attr_row) {
            $_tmp_attr_id_list = [];
            foreach ($attr_row['attr_list'] as $attr) {
                $_tmp_attr_id_list[] = intval($attr['attr_id']);
            }
            sort($_tmp_attr_id_list);
            if ($_tmp_attr_id_list == $attr_id_list) {
                $attr_data[$i]['sell_num'] = intval($attr_data[$i]['sell_num']) + intval($num);
                break;
            }
        }
        $miaosha_goods->attr = json_encode($attr_data, JSON_UNESCAPED_UNICODE);
        $res = $miaosha_goods->save();
        return $res;
    }

    /**
     * @return array
     * 自定义表单的判断
     */
    private function getForm()
    {
        $form = json_decode($this->form, true);
        $form_list = [];
        if ($form['is_form'] == 1) {
            $form_list = $form['list'];
            foreach ($form_list as $index => $value) {
                if ($value['required'] == 1) {
                    if (in_array($value['type'], ['radio', 'checkbox'])) {
                        $is_true = false;
                        foreach ($value['default_list'] as $k => $v) {
                            if ($v['is_selected'] == 1) {
                                $is_true = true;
                            }
                        }
                        if (!$is_true) {
                            return [
                                'code' => 1,
                                'msg' => '请填写' . $form['name'] . '，加“*”为必填项',
                                'name' => $value['name']
                            ];
                        }
                    } else {
                        if (!$value['default'] && $value['default'] != 0) {
                            return [
                                'code' => 1,
                                'msg' => '请填写' . $form['name'] . '，加“*”为必填项',
                                'name' => $value['name']
                            ];
                        }
                    }
                }
                if (in_array($value['type'], ['radio', 'checkbox'])) {
                    $d = [];
                    foreach ($value['default_list'] as $k => $v) {
                        if ($v['is_selected'] == 1) {
                            $d[] = $v['name'];
                        }
                    }
                    $form_list[$index]['default'] = implode(',', $d);
                }
            }
        }
        return $form_list;
    }
}
