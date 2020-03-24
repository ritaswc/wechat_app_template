<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/3
 * Time: 16:20
 */

namespace app\modules\api\models\order;


use app\models\BargainOrder;
use app\models\Cart;
use app\models\common\api\CommonOrder;
use app\models\Goods;
use app\models\Model;
use app\models\Option;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Register;
use app\models\task\order\OrderAutoCancel;
use app\models\UserCoupon;
use app\utils\PinterOrder;

class OrderSubmitForm extends OrderForm
{
    public $user;
    public $payment;
    public $use_integral;
    public $formId;

    public function rules()
    {
        $rules = [
            [['payment', 'use_integral'], 'integer'],
            [['formId'], 'trim']
        ];
        return array_merge(parent::rules(), $rules);
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $res = CommonOrder::checkOrder([
                'mobile' => $this->address ? $this->address['mobile'] : ''
            ]);
            if ($res['code'] === 1) {
                return $res;
            }

            $mchListData = $this->getMchListData(true);
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage()
            ];
        }


        $order_id_list = [];
        $level = $this->level;
        $address = (object)($this->address);

        $t = \Yii::$app->db->beginTransaction();
        foreach ($mchListData as &$mch) {
            $checkMchData = $this->checkMchData($mch);
            if ($this->use_integral == 2) {
                $mch['integral'] = ['forehead' => 0, 'forehead_integral' => 0];
                $mch['give'] = 0;
            }
            if ($checkMchData['code'] == 1) {
                return $checkMchData;
            }

            $payPrice = $this->getPayPrice($mch);

            $order = new Order();
            $order->store_id = $this->store_id;
            $order->user_id = $this->user_id;
            $order->order_no = $this->getOrderNo();
            $order->pay_price = $payPrice;
            if (isset($mch['picker_coupon']) && !empty($mch['picker_coupon'])) {
                $order->user_coupon_id = $mch['picker_coupon']['user_coupon_id'];
                $order->coupon_sub_price = $mch['picker_coupon']['sub_price'];
                // 查找优惠券优惠的商品
                $this->pickerGoods($mch);
            }
            if (isset($mch['plugin_type'])) {
                $order->type = $mch['plugin_type'];
            }
            $order->addtime = time();
            $order->first_price = 0;
            $order->second_price = 0;
            $order->third_price = 0;
            $order->content = $mch['content'];
            $order->is_offline = $mch['offline'];
            $order->integral = json_encode($mch['integral'], JSON_UNESCAPED_UNICODE);
            $order->version = hj_core_version();
            $order->mch_id = $mch['mch_id'];
            if ($mch['mch_id'] == 0) {
                $order->discount = $level['discount'];
            } else {
                $order->discount = 10;
            }
            if ($this->payment == 2) {
                $order->pay_type = 2;
                $order->is_pay = 0;
            }
            if ($this->payment == 3) {
                $order->pay_type = 3;
                $order->is_pay = 0;
            }
            if ($mch['offline'] == 0) {
                $order->address = $address->province . $address->city . $address->district . $address->detail;
                $order->mobile = $address->mobile;
                $order->name = $address->name;
                $order->address_data = json_encode([
                    'province' => trim($address->province),
                    'city' => trim($address->city),
                    'district' => trim($address->district),
                    'detail' => trim($address->detail),
                ], JSON_UNESCAPED_UNICODE);
                $order->express_price = $mch['express_price'];
                $order->total_price = $mch['total_price'] + $mch['express_price'];
            } else {
                $order->name = $mch['offline_name'];
                $order->mobile = $mch['offline_mobile'];
                $order->shop_id = $mch['shop']['id'];
                $order->total_price = $mch['total_price'];
                $order->express_price = 0;
            }
            if ($order->save()) {

                // 处理订单生成之后其他相关数据
                $orderRes = $this->insertData($mch, $order);
                if ($orderRes['code'] == 1) {
                    $t->rollBack();
                    return $orderRes;
                }

                $printer_order = new PinterOrder($this->store_id, $order->id, 'order', 0);
                $printer_order->print_order();
                $order_id_list[] = $order->id;
            } else {
                $t->rollBack();
                return $this->getErrorResponse($order);
            }
        }
        if (count($order_id_list) > 0) {
            $t->commit();

            // 任务执行延时，标识n秒后执行此任务
            $delay_seconds = \Yii::$app->controller->store->over_day * 3600;
            foreach ($order_id_list as $item) {
                if ($delay_seconds > 0) {
                    \Yii::$app->task->create(OrderAutoCancel::className(), $delay_seconds, [
                        // 任务自定义参数，选填，将在执行TaskRunnable->run()传入
                        'order_id' => $item,
                        'order_type' => 'STORE',
                        'store_id' => $this->getCurrentStoreId(),
                    ], '商城订单自动取消');
                }
            }

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

    // 获得实际支付金额
    private function getPayPrice($mch)
    {
        $goods_list = $mch['goods_list'];
        $payPrice = $mch['level_price'];
        if ($this->use_integral == 1) {
            $payPrice -= $mch['integral']['forehead'];
        }
        if (isset($mch['picker_coupon'])) {
            $pickerCoupon = $mch['picker_coupon'];
            if ($pickerCoupon['sub_price'] > 0) {

                $coupon_price = 0;

                if ($pickerCoupon['appoint_type'] == 1 && $pickerCoupon['cat_id_list'] != null) {
                    foreach ($goods_list as $goods) {
                        foreach ($goods['cat_id'] as $v1) {
                            if (in_array($v1, $pickerCoupon['cat_id_list'])) {
                                $coupon_price += floatval($goods['level_price']);
                                break;
                            };
                        };
                    }
                } else if ($pickerCoupon['appoint_type'] == 2 && $pickerCoupon['goods_id_list'] != null) {

                    foreach ($goods_list as $goods) {
                        if (in_array($goods['goods_id'], $pickerCoupon['goods_id_list'])) {
                            $coupon_price += floatval($goods['level_price']);
                        };
                    }
                };
                if ($pickerCoupon['sub_price'] > $coupon_price && $coupon_price > 0) {
                    $payPrice -= $coupon_price;
                } else {
                    $payPrice -= $pickerCoupon['sub_price'];
                }
            }
        }
        $payPrice = $payPrice >= 0 ? $payPrice : 0;
        if ($mch['express_price'] > 0 && $mch['offline'] == 0) {
            $payPrice += $mch['express_price'];
        }

        return $payPrice >= 0 ? $payPrice : 0;
    }

    // 检查数据
    private function checkMchData($mch)
    {
        if (empty($mch['goods_list'])) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已删除'
            ];
        }
        $checkFormData = $this->checkFormData($mch);
        if ($checkFormData['code'] == 1) {
            return $checkFormData;
        }

        $checkSendType = $this->checkSendType($mch);
        if ($checkSendType['code'] == 1) {
            return $checkSendType;
        }

        $checkCoupon = $this->checkCoupon($mch);
        if ($checkCoupon['code'] == 1) {
            return $checkCoupon;
        }

        $checkGoods = $this->checkGoods($mch);
        if ($checkGoods['code'] == 1) {
            return $checkGoods;
        }

        return ['code' => 0, 'msg' => 'success'];
    }

    // 检测优惠券是否可使用
    private function checkCoupon($mch)
    {
        if (empty($mch['picker_coupon'])) {
            return [
                'code' => 0,
                'msg' => ''
            ];
        }
        $ok = false;
        foreach ($mch['coupon_list'] as $item) {
            if ($item['user_coupon_id'] == $mch['picker_coupon']['user_coupon_id']) {
                $ok = true;
            }
        }
        if (!$ok) {
            return [
                'code' => 1,
                'msg' => '该优惠券已过期'
            ];
        } else {
            return [
                'code' => 0,
                'msg' => ''
            ];
        }
    }

    // 检测发货方式
    private function checkSendType($mch)
    {
        if ($mch['mch_id'] == 0) {
            if ($mch['offline'] == 0) {
                if (!$this->address) {
                    return [
                        'coe' => 1,
                        'msg' => '收货地址不存在'
                    ];
                }
                if ($mch['offer_rule'] && $mch['offer_rule']['is_allowed'] == 1) {
                    return [
                        'code' => 1,
                        'msg' => $mch['offer_rule']['msg']
                    ];
                }
                if ($mch['is_area'] == 1) {
                    return [
                        'code' => 1,
                        'msg' => '所选地区自营商品暂时无货'
                    ];
                }
            } else {
                if (isset($mch['shop']) == false) {
                    return [
                        'code' => 1,
                        'msg' => '请选择自提门店'
                    ];
                }
                if (!(isset($mch['offline_name']) && $mch['offline_name']) || !(isset($mch['offline_mobile']) && $mch['offline_mobile'])) {
                    return [
                        'code' => 1,
                        'msg' => '请输入自提信息'
                    ];
                }
                $option = Option::getList('mobile_verify', \Yii::$app->controller->store->id, 'admin', 1);
                if ($option['mobile_verify']) {
                    if (!preg_match(Model::MOBILE_VERIFY, $mch['offline_mobile'])) {
                        return [
                            'code' => 1,
                            'msg' => '请输入正确的手机号'
                        ];
                    }
                }
//                if (!preg_match(Model::MOBILE_PATTERN, $mch['offline_mobile'])) {
//                    return [
//                        'code' => 1,
//                        'msg' => '请输入正确的联系方式'
//                    ];
//                }
            }
        } else {
            if (!$this->address) {
                return [
                    'coe' => 1,
                    'msg' => '收货地址不存在'
                ];
            }
        }
        return ['code' => 0, 'msg' => ''];
    }

    // 检测自定义表单
    private function checkFormData($mch)
    {
        if (!isset($mch['form'])) {
            return [
                'code' => 0,
                'msg' => ''
            ];
        }
        $form = $mch['form'];
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
        return [
            'code' => 0,
            'msg' => ''
        ];
    }

    // 检测商品相关
    private function checkGoods($mch)
    {
        foreach ($mch['goods_list'] as $goods) {
            $attr_id_list = [];
            foreach ($goods['attr_list'] as $item) {
                array_push($attr_id_list, $item['attr_id']);
            }
            $_goods = Goods::findOne($goods['goods_id']);
            if ($_goods->type != 2) {
                if (!$_goods->numSub($attr_id_list, $goods['num'])) {
                    return [
                        'code' => 1,
                        'msg' => '订单提交失败，商品“' . $_goods->name . '”库存不足',
                        'attr_id_list' => $attr_id_list,
                        'attr_list' => $goods['attr_list'],
                    ];
                }
            }
        }
        return ['code' => 0, 'msg' => ''];
    }

    public function getOrderNo()
    {
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

    // 优惠券可优惠的商品总额计算
    private function pickerGoods(&$mch)
    {
        $totalPrice = 0;
        $pickerCoupon = $mch['picker_coupon'];
        if (empty($pickerCoupon)) {
            return;
        }
        foreach ($mch['goods_list'] as $item) {
            if ($pickerCoupon['appoint_type'] == 1) {
                if ($pickerCoupon['cat_id_list'] !== null) {
                    $catIdList = $pickerCoupon['cat_id_list'];
                    if (array_intersect($item['cat_id'], $catIdList)) {
                        $totalPrice += $item['price'];
                        $mch['picker_coupon']['goods_id'][] = $item['goods_id'];
                    }
                } else {
                    $totalPrice += $item['price'];
                    $mch['picker_coupon']['goods_id'][] = $item['goods_id'];
                }
            } else if ($pickerCoupon['appoint_type'] == 2) {
                if ($pickerCoupon['goods_id_list'] !== null) {
                    $goodsIdList = $pickerCoupon['goods_id_list'];
                    if (in_array($item['goods_id'], $goodsIdList)) {
                        $totalPrice += $item['price'];
                        $mch['picker_coupon']['goods_id'][] = $item['goods_id'];
                    }
                } else {
                    $totalPrice += $item['price'];
                    $mch['picker_coupon']['goods_id'][] = $item['goods_id'];
                }
            } else {
                $totalPrice += $item['price'];
                $mch['picker_coupon']['goods_id'][] = $item['goods_id'];
            }
        }
        $mch['picker_coupon']['total_price'] = $totalPrice;
    }

    private function insertData($mch, $order)
    {
        // 存入下单表单
        if ($mch['form'] && $mch['form']['is_form'] == 1) {
            foreach ($mch['form']['list'] as $index => $value) {
                $order_form = new \app\models\OrderForm();
                $order_form->store_id = $this->store_id;
                $order_form->order_id = $order->id;
                $order_form->key = $value['name'];
                $order_form->value = $value['default'];
                $order_form->type = $value['type'];
                $order_form->is_delete = 0;
                $order_form->save();
            }
        }

        // 减去当前用户账户积分
        if ($mch['integral'] && $mch['integral']['forehead_integral'] > 0) {
            $this->user->integral -= $mch['integral']['forehead_integral'];
            if ($this->user->integral < 0) {
                return [
                    'code' => 1,
                    'msg' => '积分不足'
                ];
            }
            $register = new Register();
            $register->store_id = $this->store->id;
            $register->user_id = $this->user->id;
            $register->register_time = '..';
            $register->addtime = time();
            $register->continuation = 0;
            $register->type = 4;
            $register->integral = '-' . $mch['integral']['forehead_integral'];
            $register->order_id = $order->id;
            $register->save();
            $this->user->save();
        }

        // 计算商品相关
        $goods_list = $mch['goods_list'];
        $goodsPrice = 0;
        foreach ($goods_list as $goods) {
            // 删除购物车
            if (isset($goods['cart_id'])) {
                Cart::updateAll(['is_delete' => 1], ['id' => $goods['cart_id']]);
            }

            // 标记正在砍价为成功
            if (isset($goods['bargain_order_id'])) {
                BargainOrder::updateAll(['status' => 1], ['id' => $goods['bargain_order_id']]);
            }
            $order_detail = new OrderDetail();
            $order_detail->order_id = $order->id;
            $order_detail->goods_id = $goods['goods_id'];
            $order_detail->num = $goods['num'];
            if ($goods['is_level'] && $goods['is_level'] == 1) {
                $order_detail->is_level = $goods['is_level'];
            } else {
                $order_detail->is_level = 0;
            }
            $payPrice = $goods['level_price'];
            if ($mch['picker_coupon'] && !empty($mch['picker_coupon'])) {
                if (in_array($goods['goods_id'], $mch['picker_coupon']['goods_id']) && $mch['picker_coupon']['total_price'] > 0) {
                    $pickerCouponSubPrice = doubleval($mch['picker_coupon']['sub_price'] * doubleval($goods['price']) / $mch['picker_coupon']['total_price']);
                    $payPrice -= $pickerCouponSubPrice;
                }
                // 删除优惠券
                UserCoupon::updateAll(['is_use' => 1], ['id' => $mch['picker_coupon']['user_coupon_id']]);
            }
            if ($this->use_integral == 1) {
                if ($goods['resIntegral'] && $goods['resIntegral']['forehead'] > 0) {
                    $payPrice -= $goods['resIntegral']['forehead'];
                }
            }
            $payPrice = $payPrice >= 0 ? sprintf('%.2f', $payPrice) : 0;
            $goodsPrice += $payPrice;
            $orderPrice = floatval($order->pay_price) - floatval($order->express_price);
            if ($goodsPrice > $orderPrice) {
                $payPrice = $payPrice - ($goodsPrice - $orderPrice);
                $goodsPrice = $orderPrice;
            }
            $order_detail->total_price = $payPrice;
            $order_detail->addtime = time();
            $order_detail->is_delete = 0;
            $order_detail->attr = json_encode($goods['attr_list'], JSON_UNESCAPED_UNICODE);
            $order_detail->pic = $goods['goods_pic'];
            if ($goods['give'] > 0) {
                $order_detail->integral = $goods['give'];
            } else {
                $order_detail->integral = 0;
            }

            $attr_id_list = [];
            foreach ($goods['attr_list'] as $item) {
                array_push($attr_id_list, $item['attr_id']);
            }

            if (!$order_detail->save()) {
                return [
                    'code' => 1,
                    'msg' => '订单提交失败，请稍后再重试',
                    'error' => $order_detail->errors
                ];
            }
        }

        return [
            'code' => 0,
            'msg' => ''
        ];
    }
}