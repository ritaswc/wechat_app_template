<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/17
 * Time: 11:48
 */

namespace app\modules\api\models\miaosha;

use app\models\Address;
use app\models\Attr;
use app\models\AttrGroup;
use app\models\common\api\CommonOrder;
use app\models\common\CommonFormId;
use app\models\common\CommonGoods;
use app\models\Coupon;
use app\models\FreeDeliveryRules;
use app\models\Goods;
use app\models\MiaoshaGoods;
use app\models\Model;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsSetting;
use app\models\Option;
use app\models\PostageRules;
use app\models\Store;
use app\models\task\order\OrderAutoCancel;
use app\models\User;
use app\models\UserCoupon;
use app\utils\PinterOrder;
use app\modules\api\models\ApiModel;
use app\modules\api\models\OrderData;
use app\models\TerritorialLimitation;
use app\models\Register;

class OrderSubmitForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $version;
    public $store;
    public $address;

    public $address_id;
    public $cart_id_list;
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
    public $formId;

    public function rules()
    {
        return [
            [['cart_id_list', 'goods_info', 'content', 'address_name', 'address_mobile'], 'string'],
            [['address_id',], 'required', 'on' => "EXPRESS"],
            [['address_name', 'address_mobile'], 'required', 'on' => "OFFLINE"],
            [['user_coupon_id', 'offline', 'shop_id', 'use_integral'], 'integer'],
            [['offline'], 'default', 'value' => 0],
            [['payment'], 'default', 'value' => 0],
            [['form', 'formId'], 'safe'],
            [['payment'], 'integer', 'message' => '请选择支付方式'],
//            [['address_mobile'], 'match', 'pattern' => Model::MOBILE_PATTERN, 'message' => '手机号错误']
        ];
    }

    public function attributeLabels()
    {
        return [
            'address_id' => '收货地址',
            'address_name' => '收货人',
            'address_mobile' => '联系电话'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $t = \Yii::$app->db->beginTransaction();

        $res = CommonOrder::checkOrder([
            'mobile' => $this->address_mobile
        ]);
        if ($res['code'] === 1) {
            return $res;
        }

        if ($this->offline == 0) {
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
            $option = Option::getList('mobile_verify', \Yii::$app->controller->store->id, 'admin', 1);
            if ($option['mobile_verify']) {
                if (!preg_match(Model::MOBILE_VERIFY, $address->mobile)) {
                    return [
                        'code' => 1,
                        'msg' => '请输入正确的手机号'
                    ];
                }
            }

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
                $setting = MsSetting::findOne([
                    'store_id' => $this->store_id,
                    'is_area' => 1,
                ]);
                if ($setting) {
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
        } else {
        }
        if (!in_array($this->payment, [0, 2, 3])) {
            return [
                'code' => 1,
                'msg' => '请选择支付方式'
            ];
        }
        $store = Store::findOne($this->store_id);
        $this->store = $store;
        $data = $this->getGoodsListByGoodsInfo($this->goods_info);
        if ($data['code'] == 1) {
            return $data;
        }
        $goods_list = empty($data['list']) ? [] : $data['list'];
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

        if (empty($goods_list)) {
            return [
                'code' => 1,
                'msg' => '订单提交失败，所选商品库存不足或已下架',
            ];
        }

        $total_price_1 = $total_price + $express_price;

        // 获取用户当前积分
        $user = User::findOne(['id' => $this->user_id, 'type' => 1, 'is_delete' => 0]);
        if ($user->integral < $resIntegral['forehead_integral']) {
            $resIntegral['forehead_integral'] = $user->integral;
            $resIntegral['forehead'] = sprintf("%.2f", $user->integral / $store->integral);
        }

        $order = new MsOrder();
        $order->store_id = $this->store_id;
        $order->user_id = $this->user_id;
        $order->order_no = $this->getOrderNo();

        //此处计算所有的优惠措施
//        $total_price_2 = $total_price; //实际支付金额
        $total_price_2 = $data['miaosha_data']['level_price']; //实际支付金额
        //减去 优惠券（不含运费
        $goods = $goods_list[0];
        if ($this->user_coupon_id && $goods->coupon == 1) {
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
            if ($coupon && $goods_total_pay_price >= $coupon->min_price && !empty($coupon->goods_id_list) && !empty($coupon->goods_id_list)) {
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
        $discount = 10;
//        if ($goods->is_discount == 1) {
//            $level = Level::find()->where(['store_id' => $this->store_id, 'level' => \Yii::$app->user->identity->level])->asArray()->one();
//            if ($level) {
//                $discount = $level['discount'];
//            }
//
//            $total_price_2 = max(0.01, round($total_price_2 * $discount / 10, 2));
//        }

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
        $order->goods_id = $goods_list[0]->goods_id;
        $order->attr = json_encode($goods_list[0]->attr_list, JSON_UNESCAPED_UNICODE);
        $order->pic = $goods_list[0]->goods_pic;
        $order->integral_amount = $goods_list[0]->give;
        $order->num = $goods_list[0]->num;
        $order->is_sum = 1;

        $msSetting = MsSetting::findOne($this->store_id);
        $unpaid = 1;
        if ($msSetting) {
            $unpaid = $msSetting->unpaid;
        }
        $order->limit_time = time() + ($unpaid * 60);

        if ($this->payment == 2) {
            $order->pay_type = 2;
            $order->is_pay = 0;
        }
        if ($this->payment == 3) {
            $order->pay_type = 3;
            $order->is_pay = 0;
        }

        if ($order->save()) {
            // 减去当前用户账户积分
            if ($resIntegral['forehead_integral'] > 0) {
                $user->integral -= $resIntegral['forehead_integral'];
                $register = new Register();
                $register->store_id = $this->store->id;
                $register->user_id = $user->id;
                $register->register_time = '..';
                $register->addtime = time();
                $register->continuation = 0;
                $register->type = 5;
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
                $attr_id_list = [];
                foreach ($goods->attr_list as $item) {
                    array_push($attr_id_list, $item['attr_id']);
                }
                $_goods = MsGoods::findOne($goods->goods_id);
                if (!$_goods->numSub($attr_id_list, $goods->num)) {
                    $t->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '订单提交失败，商品“' . $_goods->name . '”库存不足',
                        'attr_id_list' => $attr_id_list,
                        'attr_list' => $goods->attr_list,
                    ];
                }
            }
            $printer_order = new PinterOrder($this->store_id, $order->id, 'order', 1);
            $res = $printer_order->print_order();


            $res = CommonFormId::save([
                [
                    'form_id' => $this->formId
                ]
            ]);

            $t->commit();

            $delay_seconds = $unpaid * 60;
            if ($delay_seconds > 0) {
                \Yii::$app->task->create(OrderAutoCancel::className(), $delay_seconds, [
                    // 任务自定义参数，选填，将在执行TaskRunnable->run()传入
                    'order_id' => $order->id,
                    'order_type' => 'MIAOSHA',
                    'store_id' => $this->getCurrentStoreId(),
                ], '秒杀订单自动取消');
            }

            return [
                'code' => 0,
                'msg' => '订单提交成功',
                'data' => (object)[
                    'order_id' => $order->id,
                ],
            ];
        } else {
            $t->rollBack();
            return $this->getErrorResponse($order);
        }
    }


    /**
     * @param string $goods_info
     * eg.{"goods_id":"22","attr":[{"attr_group_id":1,"attr_group_name":"颜色","attr_id":3,"attr_name":"橙色"},{"attr_group_id":2,"attr_group_name":"尺码","attr_id":2,"attr_name":"M"}],"num":1}
     */
    private function getGoodsListByGoodsInfo($goods_info)
    {
        $goods_info = json_decode($goods_info);
        // 进行行锁
        $sql = 'select * from ' . MiaoshaGoods::tableName() . " where id={$goods_info->goods_id} and is_delete=0 and open_date='" . date('Y-m-d') . "' and start_time=" . date('H') . " for update";
        $miaosha_goods = \Yii::$app->db->createCommand($sql)->queryOne();
        $miaosha_goods = $this->array_to_object($miaosha_goods);

//        $miaosha_goods = MiaoshaGoods::findOne([
//            'id' => $goods_info->goods_id,
//            'is_delete' => 0,
//            'open_date' => date('Y-m-d'),
//            'start_time' => date('H'),
//        ]);
        if (!$miaosha_goods) {
            return [
                'code' => 1,
                'msg' => '秒杀活动已结束'
            ];
        }

        $goods = MsGoods::findOne([
            'id' => $miaosha_goods->goods_id,
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

        if ($miaosha_goods->buy_max > 0) {
            if ($miaosha_goods->buy_max < $goods_info->num) {
                return [
                    'code' => 1,
                    'msg' => "购买数量超过限制！ 商品“" . $goods['name'] . '”最多允许购买' . $miaosha_goods->buy_max . '件，请返回重新下单',
                ];
            }
        }
        if ($miaosha_goods->buy_limit > 0) {
            $buy_limit = MsOrder::find()
                ->andWhere(['user_id' => $this->user_id, 'is_cancel' => 0, 'goods_id' => $goods->id, 'is_delete' => 0])
                ->andWhere([
                    'between',
                    'addtime',
                    strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':00:00'),
                    strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':59:59')
                ])
                ->count();

            if ($buy_limit >= $miaosha_goods->buy_limit) {
                return [
                    'code' => 1,
                    'msg' => '当前活动限购' . $miaosha_goods->buy_limit . '单',
                ];
            }
        }
//        $res = $miaosha_goods->is_valid($goods_info,$this->user_id);
//        if ($res['code'] == 1) {
//            return $res;
//        }
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
        $goods_pic = isset($goods_attr_info['pic']) ? $goods_attr_info['pic'] ?: $goods->cover_pic : $goods->cover_pic;
        $goods_item = (object)[
            'goods_id' => $goods->id,
            'goods_name' => $goods->name,
            'goods_pic' => $goods_pic,
//            'goods_pic' => $goods->getGoodsPic(0)->pic_url,
            'num' => $goods_info->num,
            'price' => doubleval(empty($goods_attr_info['price']) ? $goods->original_price : $goods_attr_info['price']) * $goods_info->num,
            'attr_list' => $attr_list,
            'give' => 0,
            'freight' => $goods->freight,
            'integral' => $goods->integral,
            'weight' => $goods->weight,
            'full_cut' => $goods->full_cut,
            'single_price' => doubleval(empty($goods_attr_info['price']) ? $goods->original_price : $goods_attr_info['price']),
            'coupon' => $goods->coupon,
            'is_discount' => $goods->is_discount,
        ];

        //秒杀价计算
//        $miaosha_data = $this->getMiaoshaData($goods, $attr_id_list);
        $miaosha_goods = MiaoshaGoods::findOne([
            'goods_id' => $goods->id,
            'store_id' => $this->getCurrentStoreId(),
            'is_delete' => 0,
            'open_date' => date('Y-m-d'),
            'start_time' => intval(date('H')),
        ]);

        $goodsData = [
            'attr' => $miaosha_goods['attr'],
            'price' => $goods->original_price,
            // 'is_level' => $goods->is_discount,
            'is_level' => $miaosha_goods['is_level'],
        ];

        $miaosha_data = CommonGoods::currentGoodsAttr($goodsData, $attr_id_list, [
            'type' => 'MIAOSHA',
            'original_price' => $goods->original_price,
            'id' => $miaosha_goods['id'],
        ]);
        $miaosha_data['level_price'] = $miaosha_data['level_price'] * $goods_info->num;
        //下单判断是后端要用到完整的秒杀库存
        $miaosha_data['miaosha_num'] = $miaosha_data['miaosha_num_count'];

        if ($miaosha_data) {
            $res = $this->getMiaoshaPrice($miaosha_data, $goods, $attr_id_list, $goods_info->num);

            if ($res !== false) {
                if ($res['miaosha_price_num'] <= 0) {
                    return [
                        'code' => 1,
                        'msg' => '订单提交失败，商品“' . $goods->name . '”库存不足',
                    ];
                }

                $goods_item->price = $miaosha_data['goods_price'] * $res['miaosha_price_num'];
                $goods_item->single_price = $res['single_price'];
                $this->setMiaoshaSellNum($miaosha_data['id'], $attr_id_list, $res['miaosha_price_num']);
            }
        }

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
                $full_cut = json_decode([
                    'pieces' => 0,
                    'forehead' => 0,
                ], true);
            }
            if ((empty($full_cut['pieces']) || $goods_item->num < ($full_cut['pieces'] ?: 0)) && (empty($full_cut['forehead']) || $goods_item->price < ($full_cut['forehead'] ?: 0))) {
                $express_price = PostageRules::getExpressPrice($this->store_id, $this->address->city_id, $goods, $goods_item->num, $this->address->province_id);
            }
        }

        $total_price += $goods_item->price;

        return [
            'miaosha_data' => $miaosha_data,
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
            $order_no = 'M' . date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_no = MsOrder::find()->where(['order_no' => $order_no])->exists();
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
            $goods_price = $goods->original_price;
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
                'single_price' => $miaosha_price
            ];
        } else {
            return [
                'res' => '库存不足',
                'miaosha_price_num' => 0,
                'original_price_num' => 0,
                'single_price' => $miaosha_price
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
            'single_price' => $miaosha_price
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

    function array_to_object($arr)
    {
        if (gettype($arr) != 'array') {
            return;
        }
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $arr[$k] = (object)array_to_object($v);
            }
        }

        return (object)$arr;
    }
}
